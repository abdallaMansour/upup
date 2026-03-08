<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('record_date');
            $table->time('record_time')->nullable();
            $table->enum('type', [
                'honor',           // تكريم
                'success',         // نجاح
                'championship',    // بطولة
                'volunteering',    // تطوع
                'appreciation',    // شهادة تقدير
                'competition',     // مسابقة
            ]);
            $table->string('title');
            $table->string('place')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('school')->nullable();
            $table->foreignId('certificate_image_document_id')->nullable()->constrained('user_documents')->nullOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_achievements');
    }
};
