<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Quiz extends Model
{
    // Only what the teacher types in the form. Status, link and dates are set by our code.
    protected $fillable = [
        'title',
        'period_hours',
        'passing_score',
        'max_attempts',
        'randomize_questions',
        'randomize_options',
        'negative_marking',
        'show_answers',
    ];

    protected function casts(): array
    {
        return [
            'passing_score' => 'decimal:2',
            'randomize_questions' => 'boolean',
            'randomize_options' => 'boolean',
            'negative_marking' => 'boolean',
            'show_answers' => 'boolean',
            'link_active' => 'boolean',
            'published_at' => 'datetime',
            'closes_at' => 'datetime',
        ];
    }

    // Every new quiz automatically gets its secret link token
    protected static function booted(): void
    {
        static::creating(function (Quiz $quiz) {
            $quiz->share_token = $quiz->share_token ?: Str::random(24);
            $quiz->reference = $quiz->reference ?: Quiz::generateReference();
        });
    }

    // 8 characters, no I, O, 0 or 1 (easy to confuse), and always unique
    public static function generateReference(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
            }
        } while (static::where('reference', $code)->exists());

        return $code;
    }

    // What the student typed in the search bar (dash, spaces, small letters are accepted)
    public static function findByReference(string $input): ?self
    {
        $code = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $input));

        return static::where('reference', $code)->first();
    }

    // Shown as K7QM-A4XP
    public function displayReference(): string
    {
        return substr($this->reference, 0, 4) . '-' . substr($this->reference, 4);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('position');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(QuizInvitation::class);
    }

    // Can students start or continue this quiz right now?
    public function isOpen(): bool
    {
        return $this->status === 'published'
            && $this->link_active
            && $this->closes_at !== null
            && now()->lessThan($this->closes_at);
    }

    // After the first submission, questions and answers must not change
    public function hasSubmissions(): bool
    {
        return $this->attempts()->whereNotNull('submitted_at')->exists();
    }

    public function totalPoints(): float
    {
        return (float) $this->questions()->sum('points');
    }
}
