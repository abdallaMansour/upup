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
            $table->json('education_linked_sections')->nullable()->after('cover_image_document_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_childhood_stages', function (Blueprint $table) {
            $table->dropColumn('education_linked_sections');
        });
    }
};
