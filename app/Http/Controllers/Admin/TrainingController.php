<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingNode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class TrainingController extends Controller
{
    public function index(): Response
    {
        $trainings = Training::query()
            ->with('nodes')
            ->orderBy('name')
            ->paginate(15)
            ->through(function (Training $training) {
                $nodes = $training->nodes->keyBy('id');

                $buildTree = function ($parentId = null) use (&$buildTree, $nodes) {
                    return $nodes
                        ->filter(fn ($node) => $node->parent_id === $parentId)
                        ->map(function ($node) use (&$buildTree) {
                            return [
                                'id' => (string) $node->id,
                                'title' => $node->title,
                                'video_url' => $node->video_path
                                    ? Storage::disk('public')->url($node->video_path)
                                    : null,
                                'video' => null,
                                'children' => $buildTree($node->id),
                            ];
                        })
                        ->values()
                        ->all();
                };

                return [
                    'id' => $training->id,
                    'name' => $training->name,
                    'nodes_count' => $training->nodes->count(),
                    'nodes' => $buildTree(),
                ];
            });

        return Inertia::render('Admin/Trainings/Index', [
            'trainings' => $trainings,
        ]);
    }

    public function checkName(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:trainings,name',
            ],
        ], [
            'name.required' => 'نام آموزش را وارد کنید.',
            'name.unique' => 'این نام آموزش قبلاً ثبت شده است.',
            'name.max' => 'نام آموزش نباید بیشتر از ۲۵۵ کاراکتر باشد.',
        ]);

        return back();
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:trainings,name',
            ],
            'nodes' => ['present', 'array'],
        ], [
            'name.required' => 'نام آموزش را وارد کنید.',
            'name.unique' => 'این نام آموزش قبلاً ثبت شده است.',
        ]);

        $nodes = $validated['nodes'];

        $this->validateNodes($nodes, 'nodes');

        DB::transaction(function () use ($validated, $nodes) {
            $training = Training::create([
                'name' => trim($validated['name']),
            ]);

            $this->saveNodes($training->id, $nodes);
        });

        return back()->with('success', 'آموزش با موفقیت ذخیره شد.');
    }

    public function show(Training $training): Response
    {
        $training->load([
            'nodes' => fn ($query) => $query->orderBy('position'),
        ]);

        $nodes = $training->nodes->keyBy('id');

        $buildTree = function ($parentId = null) use (&$buildTree, $nodes) {
            return $nodes
                ->filter(fn ($node) => $node->parent_id === $parentId)
                ->map(function ($node) use (&$buildTree) {
                    return [
                        'id' => $node->id,
                        'title' => $node->title,
                        'video_url' => $node->video_path
                            ? Storage::disk('public')->url($node->video_path)
                            : null,
                        'children' => $buildTree($node->id),
                    ];
                })
                ->values()
                ->all();
        };

        return Inertia::render('Admin/Trainings/Show', [
            'training' => [
                'id' => $training->id,
                'name' => $training->name,
                'nodes' => $buildTree(),
            ],
        ]);
    }

    public function update(Request $request, Training $training): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:trainings,name,' . $training->id,
            ],
            'nodes' => ['present', 'array'],
        ]);

        $nodes = $validated['nodes'];

        $this->validateNodes($nodes, 'nodes');

        $submittedIds = [];

        $collectIds = function (array $items) use (&$collectIds, &$submittedIds) {
            foreach ($items as $item) {
                if (! empty($item['id'])) {
                    $id = (int) $item['id'];

                    if (in_array($id, $submittedIds, true)) {
                        throw ValidationException::withMessages([
                            'nodes' => 'یک سرفصل بیش از یک بار ارسال شده است.',
                        ]);
                    }

                    $submittedIds[] = $id;
                }

                $collectIds($item['children'] ?? []);
            }
        };

        $collectIds($nodes);

        $existingNodes = $training->nodes()->get()->keyBy('id');

        foreach ($submittedIds as $id) {
            if (! $existingNodes->has($id)) {
                throw ValidationException::withMessages([
                    'nodes' => 'یکی از سرفصل‌های ارسالی معتبر نیست.',
                ]);
            }
        }

        $submittedIdLookup = array_flip($submittedIds);

        DB::transaction(function () use (
            $training,
            $validated,
            $nodes,
            $existingNodes,
            $submittedIdLookup
        ) {
            $training->update([
                'name' => $validated['name'],
            ]);

            $syncNodes = function (
                array $items,
                ?int $parentId = null
            ) use (&$syncNodes, $training, $existingNodes) {
                foreach ($items as $position => $item) {
                    $node = ! empty($item['id'])
                        ? $existingNodes->get((int) $item['id'])
                        : null;

                    $oldVideoPath = $node?->video_path;

                    $data = [
                        'training_id' => $training->id,
                        'parent_id' => $parentId,
                        'title' => trim($item['title']),
                        'position' => $position,
                    ];

                    if (($item['video'] ?? null) instanceof UploadedFile) {
                        $data['video_path'] = $item['video']->store(
                            'training-videos',
                            'public'
                        );
                    }

                    if ($node) {
                        $node->update($data);
                    } else {
                        $node = TrainingNode::create($data);
                    }

                    if (isset($data['video_path']) && $oldVideoPath) {
                        Storage::disk('public')->delete($oldVideoPath);
                    }

                    $syncNodes($item['children'] ?? [], $node->id);
                }
            };

            $syncNodes($nodes);

            $deletedNodes = $existingNodes->filter(
                fn ($node) => ! isset($submittedIdLookup[$node->id])
            );

            $deletedPaths = $deletedNodes
                ->pluck('video_path')
                ->filter()
                ->unique()
                ->values()
                ->all();

            foreach ($deletedNodes as $node) {
                $node->delete();
            }

            foreach ($deletedPaths as $path) {
                Storage::disk('public')->delete($path);
            }
        });

        return back()->with('success', 'آموزش با موفقیت ویرایش شد.');
    }

    public function destroy(Training $training): RedirectResponse
    {
        $videoPaths = $training->nodes()
            ->whereNotNull('video_path')
            ->pluck('video_path')
            ->unique()
            ->values()
            ->all();

        DB::transaction(function () use ($training) {
            $training->delete();
        });

        foreach ($videoPaths as $path) {
            Storage::disk('public')->delete($path);
        }

        return back()->with('success', 'آموزش و ویدئوهای آن حذف شدند.');
    }

    private function validateNodes(array $nodes, string $path): void
    {
        foreach ($nodes as $index => $node) {
            $titlePath = "{$path}.{$index}.title";

            if (
                ! is_array($node) ||
                ! isset($node['title']) ||
                ! is_string($node['title']) ||
                trim($node['title']) === '' ||
                mb_strlen(trim($node['title'])) > 255
            ) {
                throw ValidationException::withMessages([
                    $titlePath => 'عنوان هر بخش الزامی است و حداکثر ۲۵۵ کاراکتر دارد.',
                ]);
            }

            Validator::make($node, [
                'video' => ['nullable', 'file', 'mimes:mp4,webm,mov', 'max:512000'],
            ], [
                'video.mimes' => 'فرمت ویدئو باید MP4، WebM یا MOV باشد.',
                'video.max' => 'حجم ویدئو نباید بیشتر از ۵۰۰ مگابایت باشد.',
            ])->validate();

            $children = $node['children'] ?? [];

            if (! is_array($children)) {
                throw ValidationException::withMessages([
                    "{$path}.{$index}.children" => 'ساختار زیرمجموعه‌ها معتبر نیست.',
                ]);
            }

            $this->validateNodes($children, "{$path}.{$index}.children");
        }
    }

    private function saveNodes(
        int $trainingId,
        array $nodes,
        ?int $parentId = null
    ): void {
        foreach ($nodes as $position => $item) {
            $videoPath = null;

            if (
                isset($item['video']) &&
                $item['video'] instanceof UploadedFile
            ) {
                $videoPath = $item['video']->store('training-videos', 'public');
            }

            $node = TrainingNode::create([
                'training_id' => $trainingId,
                'parent_id' => $parentId,
                'title' => trim($item['title']),
                'position' => $position,
                'video_path' => $videoPath,
            ]);

            $this->saveNodes($trainingId, $item['children'] ?? [], $node->id);
        }
    }
}
