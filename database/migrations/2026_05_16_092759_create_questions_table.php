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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('quiz_module_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->text('question_text');

            $table->text('option_a');

            $table->text('option_b');

            $table->text('option_c');

            $table->text('option_d');

            $table->enum('correct_option', ['A', 'B', 'C', 'D']);

            $table->integer('timer_seconds')->default(30);

            $table->string('image_path')->nullable();

            $table->integer('points')->default(10);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
