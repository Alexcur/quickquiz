<?php

use App\Models\Quiz;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Short quiz number that students can type, e.g. K7QMA4XP shown as K7QM-A4XP
            $table->string('reference', 12)->nullable()->unique()->after('share_token');
        });

        // Give a number to quizzes that already exist (for example your test quiz)
        foreach (DB::table('quizzes')->whereNull('reference')->pluck('id') as $id) {
            DB::table('quizzes')->where('id', $id)->update(['reference' => Quiz::generateReference()]);
        }
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('reference');
        });
    }
};
