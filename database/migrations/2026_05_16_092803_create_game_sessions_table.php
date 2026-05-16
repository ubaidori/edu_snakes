<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('quiz_module_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->json('players');

            $table->integer('active_player_index')->default(0);

            $table->foreignId('current_question_id')
                ->nullable()
                ->constrained('questions')
                ->nullOnDelete();

            $table->json('used_question_ids')->nullable();

            $table->json('settings')->nullable();

            $table->enum('status', [
                'waiting',
                'playing',
                'finished'
            ])->default('waiting');

            $table->integer('winner_player_index')->nullable();

            $table->timestamp('started_at')->nullable();

            $table->timestamp('finished_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
