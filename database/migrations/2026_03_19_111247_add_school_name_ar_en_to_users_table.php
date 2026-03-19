<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('school_name_ar', 255)->nullable()->after('education_grade_id');
            $table->string('school_name_en', 255)->nullable()->after('school_name_ar');
        });

        // Migrate existing school_name to school_name_ar
        DB::table('users')->update([
            'school_name_ar' => DB::raw('school_name'),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('school_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('school_name', 255)->nullable()->after('education_grade_id');
        });

        DB::table('users')->update([
            'school_name' => DB::raw('school_name_ar'),
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['school_name_ar', 'school_name_en']);
        });
    }
};
