<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('storage_connection_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('original_name')->nullable();
            $table->string('path'); // مسار الملف في التخزين
            $table->string('external_id')->nullable(); // معرف الملف في المنصة الخارجية
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0); // بالبايت
            $table->string('provider')->nullable(); // google_drive, wasabi, local, etc.
            $table->timestamps();

            $table->index(['user_id', 'storage_connection_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_documents');
    }
};
