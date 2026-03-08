<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_achievement_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_achievement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_document_id')->constrained()->cascadeOnDelete();
            $table->enum('media_type', ['photo', 'video']);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['user_achievement_id', 'media_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_achievement_media');
    }
};
