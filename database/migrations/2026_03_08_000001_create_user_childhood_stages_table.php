<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_childhood_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('footprint_document_id')->nullable()->constrained('user_documents')->nullOnDelete();
            $table->string('name');
            $table->string('mother_name');
            $table->string('father_name');
            $table->text('naming_reason')->nullable();
            $table->date('birth_date')->nullable();
            $table->time('birth_time')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('blood_type')->nullable();
            $table->string('doctor')->nullable();
            $table->string('birth_place')->nullable();
            $table->foreignId('first_photo_document_id')->nullable()->constrained('user_documents')->nullOnDelete();
            $table->foreignId('first_video_document_id')->nullable()->constrained('user_documents')->nullOnDelete();
            $table->foreignId('first_gift_document_id')->nullable()->constrained('user_documents')->nullOnDelete();
            $table->timestamps();

            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_childhood_stages');
    }
};
