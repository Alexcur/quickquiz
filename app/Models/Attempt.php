<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attempt extends Model
{
    // Attempts are created only by our own code, never from a form
    protected $fillable = [
        'quiz_id',
        'student_id',
        'attempt_number',
        'question_order',
        'current_position',
        'question_deadline_at',
        'started_at',
        'submitted_at',
        'score',
        'passed',
    ];

    protected function casts(): array
    {
        return [
            'question_order' => 'array',
            'question_deadline_at' => 'datetime',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'score' => 'decimal:2',
            'passed' => 'boolean',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    // An attempt that was started but not submitted yet
    public function isActive(): bool
    {
        return $this->submitted_at === null;
    }
}
