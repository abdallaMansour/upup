<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'user_height_weights',
        'user_achievements',
        'user_voices',
        'user_drawings',
        'user_visits',
        'user_injuries',
        'user_other_events',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('user_childhood_stage_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
            });
        }

        // Migrate existing data: link records to user's single childhood stage
        $stageIds = DB::table('user_childhood_stages')->pluck('id', 'user_id');

        foreach ($this->tables as $tableName) {
            foreach ($stageIds as $userId => $stageId) {
                DB::table($tableName)->where('user_id', $userId)->update(['user_childhood_stage_id' => $stageId]);
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['user_childhood_stage_id']);
            });
        }
    }
};
