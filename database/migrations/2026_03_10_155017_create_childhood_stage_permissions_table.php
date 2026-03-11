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
        Schema::create('childhood_stage_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_childhood_stage_id')->constrained()->cascadeOnDelete();
            $table->string('grantee_name');
            $table->string('grantee_email');
            $table->string('pin_hash');
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('childhood_stage_permissions');
    }
};
