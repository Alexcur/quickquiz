<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('attempt_number');                // 1 or 2
            $table->json('question_order')->nullable();                   // random order saved for this attempt
            $table->unsignedSmallInteger('current_position')->default(1); // which question the student is on
            $table->timestamp('question_deadline_at')->nullable();        // server-side timer of the current question
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();                // empty = attempt still active
            $table->decimal('score', 6, 2)->nullable();
            $table->boolean('passed')->nullable();
            $table->timestamps();

            $table->unique(['quiz_id', 'student_id', 'attempt_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
