<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('status', 20)->default('draft');          // draft or published
            $table->unsignedTinyInteger('period_hours')->default(24); // 24 or 48
            $table->decimal('passing_score', 6, 2)->default(0);       // points needed to pass
            $table->unsignedTinyInteger('max_attempts')->default(2);  // 2 trials
            $table->boolean('randomize_questions')->default(true);
            $table->boolean('randomize_options')->default(true);
            $table->boolean('negative_marking')->default(false);
            $table->boolean('show_answers')->default(true);
            $table->boolean('link_active')->default(true);            // teacher can disable the link
            $table->string('share_token', 40)->unique();              // hard-to-guess part of the link
            $table->timestamp('published_at')->nullable();
            $table->timestamp('closes_at')->nullable();               // published_at + period_hours
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
