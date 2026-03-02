<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_documents', function (Blueprint $table) {
            $table->string('type')->default('file')->after('provider');
            $table->unsignedBigInteger('parent_id')->nullable()->after('storage_connection_id');
        });

        \DB::table('user_documents')->update(['type' => 'file']);

        Schema::table('user_documents', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('user_documents')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_documents', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
        Schema::table('user_documents', function (Blueprint $table) {
            $table->dropColumn(['type', 'parent_id']);
        });
    }
};
