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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('age_stage_childhood_max')->default(11)->after('terms_and_conditions');
            $table->unsignedTinyInteger('age_stage_teenager_max')->default(17)->after('age_stage_childhood_max');
            $table->unsignedTinyInteger('age_stage_adult_max')->default(120)->after('age_stage_teenager_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['age_stage_childhood_max', 'age_stage_teenager_max', 'age_stage_adult_max']);
        });
    }
};
