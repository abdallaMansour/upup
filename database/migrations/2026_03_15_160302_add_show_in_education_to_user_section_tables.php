<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'user_height_weights',
            'user_achievements',
            'user_voices',
            'user_drawings',
            'user_visits',
            'user_injuries',
            'user_other_events',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('show_in_education')->default(false)->after('user_childhood_stage_id');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'user_height_weights',
            'user_achievements',
            'user_voices',
            'user_drawings',
            'user_visits',
            'user_injuries',
            'user_other_events',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('show_in_education');
            });
        }
    }
};
