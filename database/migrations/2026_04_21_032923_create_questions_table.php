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
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['mcq', 'subjective']);
            $table->text('question_text');
            $table->json('options')->nullable(); // Simpan A, B, C, D dalam JSON
            $table->string('correct_answer'); // Untuk MCQ: 'A', Untuk Subjective: 'keyword1,keyword2'
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
