<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop unique and add regular index in one statement (MySQL needs index for FK)
        DB::statement('ALTER TABLE user_childhood_stages DROP INDEX user_childhood_stages_user_id_unique, ADD INDEX user_childhood_stages_user_id_index (user_id)');

        Schema::table('user_childhood_stages', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->after('user_id');
            $table->foreignId('cover_image_document_id')->nullable()->after('first_gift_document_id')->constrained('user_documents')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_childhood_stages', function (Blueprint $table) {
            $table->dropForeign(['cover_image_document_id']);
            $table->dropColumn(['is_public', 'cover_image_document_id']);
        });

        DB::statement('ALTER TABLE user_childhood_stages DROP INDEX user_childhood_stages_user_id_index, ADD UNIQUE INDEX user_childhood_stages_user_id_unique (user_id)');
    }
};
