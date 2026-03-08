<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_height_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('record_date');
            $table->time('record_time')->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->foreignId('image_document_id')->nullable()->constrained('user_documents')->nullOnDelete();
            $table->foreignId('video_document_id')->nullable()->constrained('user_documents')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_height_weights');
    }
};
