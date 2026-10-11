<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_nodes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('training_id')
                ->constrained('trainings')
                ->cascadeOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('training_nodes')
                ->cascadeOnDelete();

            $table->string('title');

            $table->unsignedInteger('position')
                ->default(0);

            $table->string('video_path')
                ->nullable();

            $table->timestamps();

            $table->index([
                'training_id',
                'parent_id',
                'position',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_nodes');
    }
};
