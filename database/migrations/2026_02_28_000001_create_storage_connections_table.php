<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // google_drive, wasabi, dropbox, etc.
            $table->string('name')->nullable(); // اسم مخصص للمستخدم
            $table->boolean('is_active')->default(true);
            $table->json('credentials')->nullable(); // encrypted tokens/keys
            $table->string('root_folder_id')->nullable(); // مجلد الجذر للملفات
            $table->timestamps();

            $table->unique(['user_id', 'provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_connections');
    }
};
