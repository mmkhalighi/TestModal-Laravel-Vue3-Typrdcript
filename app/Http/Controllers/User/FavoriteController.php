<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\TrainingNode;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with('trainingNode.training')
            ->latest()
            ->paginate(15);

        return inertia('User/Favorites/Index', [
            'favorites' => $favorites,
        ]);
    }

    public function store(TrainingNode $trainingNode)
    {
        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'training_node_id' => $trainingNode->id,
        ]);

        return back()->with(
            'success',
            'سرفصل به علاقه‌مندی‌ها اضافه شد.'
        );
    }

    public function destroy(TrainingNode $trainingNode)
    {
        auth()->user()
            ->favorites()
            ->where('training_node_id', $trainingNode->id)
            ->delete();

        return back()->with(
            'success',
            'سرفصل از علاقه‌مندی‌ها حذف شد.'
        );
    }
}
