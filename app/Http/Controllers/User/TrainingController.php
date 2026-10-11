<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Support\Facades\Storage;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::query()
            ->withCount('nodes')
            ->orderBy('name')
            ->paginate(12);

        return inertia('User/Trainings/Index', [
            'trainings' => $trainings,
        ]);
    }

    public function show(Training $training)
    {
        $training->load([
            'nodes' => fn ($query) => $query->orderBy('position'),
        ]);

        $nodes = $training->nodes->keyBy('id');

        $favoriteIds = auth()->user()
            ->favorites()
            ->whereIn('training_node_id', $nodes->keys())
            ->pluck('training_node_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $favoriteLookup = array_flip($favoriteIds);

        $buildTree = function ($parentId = null) use (
            &$buildTree,
            $nodes,
            $favoriteLookup
        ) {
            return $nodes
                ->filter(fn ($node) => $node->parent_id === $parentId)
                ->map(function ($node) use (
                    &$buildTree,
                    $favoriteLookup
                ) {
                    return [
                        'id' => $node->id,
                        'title' => $node->title,
                        'video_url' => $node->video_path
                            ? Storage::disk('public')->url($node->video_path)
                            : null,
                        'is_favorite' => isset($favoriteLookup[$node->id]),
                        'children' => $buildTree($node->id),
                    ];
                })
                ->values()
                ->all();
        };

        return inertia('User/Trainings/Show', [
            'training' => [
                'id' => $training->id,
                'name' => $training->name,
            ],
            'nodes' => $buildTree(),
        ]);
    }
}
