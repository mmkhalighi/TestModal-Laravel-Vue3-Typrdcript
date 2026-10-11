<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function nodes(): HasMany
    {
        return $this->hasMany(TrainingNode::class);
    }

    public function rootNodes(): HasMany
    {
        return $this->hasMany(TrainingNode::class)
            ->whereNull('parent_id')
            ->orderBy('position');
    }
}
