<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingNode extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_id',
        'parent_id',
        'title',
        'position',
        'video_path',
    ];

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            TrainingNode::class,
            'parent_id'
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(
            TrainingNode::class,
            'parent_id'
        )->orderBy('position');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function hasVideo(): bool
    {
        return filled($this->video_path);
    }
}
