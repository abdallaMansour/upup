<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_other_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('record_date');
            $table->time('record_time')->nullable();
            $table->foreignId('media_document_id')->nullable()->constrained('user_documents')->nullOnDelete();
            $table->string('title');
            $table->text('other_info')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_other_events');
    }
};
