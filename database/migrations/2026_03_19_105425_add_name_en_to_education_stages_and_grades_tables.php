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
        Schema::table('education_stages', function (Blueprint $table) {
            $table->string('name_en', 255)->nullable()->after('name');
        });

        Schema::table('education_grades', function (Blueprint $table) {
            $table->string('name_en', 255)->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('education_stages', function (Blueprint $table) {
            $table->dropColumn('name_en');
        });

        Schema::table('education_grades', function (Blueprint $table) {
            $table->dropColumn('name_en');
        });
    }
};
