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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('education_stage_id')->nullable()->constrained('education_stages')->nullOnDelete();
            $table->foreignId('education_grade_id')->nullable()->constrained('education_grades')->nullOnDelete();
            $table->string('school_name', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['education_stage_id']);
            $table->dropForeign(['education_grade_id']);
            $table->dropColumn(['education_stage_id', 'education_grade_id', 'school_name']);
        });
    }
};
