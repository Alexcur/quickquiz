<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Attempt;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Demo data for local development only.
 * Every demo account has the password: password
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Users ----
        $teacher = User::forceCreate([
            'name' => 'Prof. Alami',
            'email' => 'teacher@quickquiz.test',
            'password' => 'password',
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        $students = [];
        foreach ([['Sara B.', 'sara'], ['Youssef M.', 'youssef'], ['Imane K.', 'imane']] as [$name, $key]) {
            $students[$key] = User::forceCreate([
                'name' => $name,
                'email' => $key . '@quickquiz.test',
                'password' => 'password',
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }

        // ---- Quiz 1: published, 15 questions, 20 points ----
        $quiz = $teacher->quizzes()->create([
            'title' => 'Networking Basics',
            'period_hours' => 24,
            'passing_score' => 10,
            'max_attempts' => 2,
        ]);
        // status and dates are not fillable on purpose, so we set them with forceFill
        $quiz->forceFill([
            'status' => 'published',
            'published_at' => now()->subHour(),
            'closes_at' => now()->addHours(23),
        ])->save();

        $this->addQuestions($quiz, $this->networkingQuestions());

        foreach (['sara', 'youssef', 'imane'] as $key) {
            $quiz->invitations()->create([
                'email' => $key . '@quickquiz.test',
                'sent_at' => now()->subHour(),
            ]);
        }

        // ---- Quiz 2: draft with only 3 questions (cannot be published yet: minimum is 15) ----
        $draft = $teacher->quizzes()->create([
            'title' => 'Introduction to PHP',
            'period_hours' => 48,
            'passing_score' => 5,
        ]);
        $this->addQuestions($draft, [
            $this->question('single', 'Which symbol starts a variable in PHP?', 1, 30, [['$', true], ['@', false], ['#', false], ['&', false]]),
            $this->trueFalse('PHP code is executed on the server.', true),
            $this->question('single', 'Which language construct prints text on the screen?', 1, 30, [['echo', true], ['write', false], ['show', false], ['out', false]]),
        ]);

        // ---- Finished attempts, to test the statistics later ----
        // c = correct, w = wrong, s = skipped (one letter per question)
        $quiz->load('questions.options');
        $this->makeAttempt($quiz, $students['sara'], 'ccccccwccccccws');
        $this->makeAttempt($quiz, $students['youssef'], 'cwccwcwscwcwcws');
        $this->makeAttempt($quiz, $students['imane'], 'ccccccwcccccccc');
    }

    private function addQuestions(Quiz $quiz, array $questions): void
    {
        foreach ($questions as $i => $q) {
            $question = $quiz->questions()->create([
                'type' => $q['type'],
                'body' => $q['body'],
                'points' => $q['points'],
                'time_limit_seconds' => $q['time'],
                'position' => $i + 1,
            ]);

            foreach ($q['options'] as $j => [$text, $isCorrect]) {
                $question->options()->create([
                    'body' => $text,
                    'is_correct' => $isCorrect,
                    'position' => $j + 1,
                ]);
            }
        }
    }

    private function question(string $type, string $body, int $points, int $time, array $options): array
    {
        return compact('type', 'body', 'points', 'time', 'options');
    }

    private function trueFalse(string $body, bool $answer): array
    {
        return $this->question('true_false', $body, 1, 30, [['True', $answer], ['False', !$answer]]);
    }

    private function networkingQuestions(): array
    {
        return [
            $this->question('single', 'Which layer of the OSI model is responsible for routing packets between networks?', 1, 30, [['Data Link', false], ['Network', true], ['Transport', false], ['Session', false]]),
            $this->question('single', 'How many bits does an IPv4 address have?', 1, 30, [['16', false], ['32', true], ['64', false], ['128', false]]),
            $this->trueFalse('A switch forwards frames using MAC addresses.', true),
            $this->question('single', 'Which protocol automatically assigns IP addresses to devices?', 1, 30, [['DHCP', true], ['DNS', false], ['ARP', false], ['FTP', false]]),
            $this->question('single', 'Which port does HTTPS use by default?', 1, 30, [['80', false], ['443', true], ['21', false], ['25', false]]),
            $this->trueFalse('A hub is smarter than a switch.', false),
            $this->question('single', 'How many usable host addresses does a /26 subnet have?', 1, 45, [['30', false], ['62', true], ['64', false], ['126', false]]),
            $this->question('single', 'Which command shows the IP configuration on Windows?', 1, 30, [['ipconfig', true], ['ifconfig', false], ['netstat', false], ['tracert', false]]),
            $this->question('single', 'Which protocol translates domain names into IP addresses?', 1, 30, [['DNS', true], ['DHCP', false], ['ICMP', false], ['SNMP', false]]),
            $this->trueFalse('The ping command uses the ICMP protocol.', true),
            $this->question('multiple', 'Which of these are private IPv4 ranges?', 2, 60, [['10.0.0.0/8', true], ['172.16.0.0/12', true], ['8.8.8.0/24', false], ['192.168.0.0/16', true]]),
            $this->question('multiple', 'Which protocols work at the Transport layer?', 2, 60, [['TCP', true], ['UDP', true], ['IP', false], ['HTTP', false]]),
            $this->question('multiple', 'Which devices work at Layer 3?', 2, 60, [['Router', true], ['Layer 3 switch', true], ['Hub', false], ['Repeater', false]]),
            $this->question('multiple', 'Which of these are routing protocols?', 2, 60, [['OSPF', true], ['RIP', true], ['BGP', true], ['ARP', false]]),
            $this->question('multiple', 'Which ports does DHCP use?', 2, 60, [['UDP 67', true], ['UDP 68', true], ['TCP 53', false], ['TCP 443', false]]),
        ];
    }

    private function makeAttempt(Quiz $quiz, User $student, string $pattern): void
    {
        $questions = $quiz->questions;

        $attempt = Attempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'attempt_number' => 1,
            'question_order' => $questions->pluck('id')->all(),
            'current_position' => $questions->count(),
            'started_at' => now()->subMinutes(40),
            'submitted_at' => now()->subMinutes(25),
        ]);

        $score = 0;

        foreach ($questions as $i => $question) {
            $result = $pattern[$i];
            $correctIds = $question->options->where('is_correct', true)->pluck('id')->all();
            $wrongId = $question->options->where('is_correct', false)->first()->id;
            $points = $result === 'c' ? (float) $question->points : 0;
            $score += $points;

            Answer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'selected_option_ids' => match ($result) {
                    'c' => $correctIds,
                    'w' => [$wrongId],
                    default => null,
                },
                'is_correct' => $result === 'c',
                'is_skipped' => $result === 's',
                'points_awarded' => $points,
                'answered_at' => $result === 's' ? null : now()->subMinutes(30),
            ]);
        }

        $attempt->update([
            'score' => $score,
            'passed' => $score >= (float) $quiz->passing_score,
        ]);
    }
}
