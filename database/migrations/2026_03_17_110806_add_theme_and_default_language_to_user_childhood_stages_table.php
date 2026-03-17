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
        Schema::table('user_childhood_stages', function (Blueprint $table) {
            $table->string('theme')->nullable()->after('cover_image_document_id');
            $table->string('default_language', 5)->nullable()->after('theme');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_childhood_stages', function (Blueprint $table) {
            $table->dropColumn(['theme', 'default_language']);
        });
    }
};
