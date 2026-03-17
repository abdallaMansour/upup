<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_childhood_stages', function (Blueprint $table) {
            $table->string('name_ar')->nullable();
            $table->string('name_en')->nullable();
            $table->string('mother_name_ar')->nullable();
            $table->string('mother_name_en')->nullable();
            $table->string('father_name_ar')->nullable();
            $table->string('father_name_en')->nullable();
            $table->text('naming_reason_ar')->nullable();
            $table->text('naming_reason_en')->nullable();
            $table->string('birth_place_ar')->nullable();
            $table->string('birth_place_en')->nullable();
            $table->string('doctor_ar')->nullable();
            $table->string('doctor_en')->nullable();
        });

        DB::table('user_childhood_stages')->orderBy('id')->chunk(100, function ($stages) {
            foreach ($stages as $stage) {
                $name = $stage->name ?? '';
                $motherName = $stage->mother_name ?? '';
                $fatherName = $stage->father_name ?? '';
                $namingReason = $stage->naming_reason ?? '';
                $birthPlace = $stage->birth_place ?? '';
                $doctor = $stage->doctor ?? '';

                DB::table('user_childhood_stages')->where('id', $stage->id)->update([
                    'name_ar' => $name,
                    'name_en' => $name,
                    'mother_name_ar' => $motherName,
                    'mother_name_en' => $motherName,
                    'father_name_ar' => $fatherName,
                    'father_name_en' => $fatherName,
                    'naming_reason_ar' => $namingReason ?: null,
                    'naming_reason_en' => $namingReason ?: null,
                    'birth_place_ar' => $birthPlace ?: null,
                    'birth_place_en' => $birthPlace ?: null,
                    'doctor_ar' => $doctor ?: null,
                    'doctor_en' => $doctor ?: null,
                ]);
            }
        });

        Schema::table('user_childhood_stages', function (Blueprint $table) {
            $table->dropColumn(['name', 'mother_name', 'father_name', 'naming_reason', 'birth_place', 'doctor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_childhood_stages', function (Blueprint $table) {
            $table->string('name')->nullable()->after('footprint_document_id');
            $table->string('mother_name')->nullable()->after('name');
            $table->string('father_name')->nullable()->after('mother_name');
            $table->text('naming_reason')->nullable()->after('father_name');
            $table->string('birth_place')->nullable()->after('blood_type');
            $table->string('doctor')->nullable()->after('birth_place');
        });

        DB::table('user_childhood_stages')->orderBy('id')->chunk(100, function ($stages) {
            foreach ($stages as $stage) {
                $name = $stage->name_ar ?? $stage->name_en ?? '';
                $motherName = $stage->mother_name_ar ?? $stage->mother_name_en ?? '';
                $fatherName = $stage->father_name_ar ?? $stage->father_name_en ?? '';
                $namingReason = $stage->naming_reason_ar ?? $stage->naming_reason_en ?? '';
                $birthPlace = $stage->birth_place_ar ?? $stage->birth_place_en ?? '';
                $doctor = $stage->doctor_ar ?? $stage->doctor_en ?? '';

                DB::table('user_childhood_stages')->where('id', $stage->id)->update([
                    'name' => $name,
                    'mother_name' => $motherName,
                    'father_name' => $fatherName,
                    'naming_reason' => $namingReason ?: null,
                    'birth_place' => $birthPlace ?: null,
                    'doctor' => $doctor ?: null,
                ]);
            }
        });

        Schema::table('user_childhood_stages', function (Blueprint $table) {
            $table->dropColumn([
                'name_ar', 'name_en',
                'mother_name_ar', 'mother_name_en',
                'father_name_ar', 'father_name_en',
                'naming_reason_ar', 'naming_reason_en',
                'birth_place_ar', 'birth_place_en',
                'doctor_ar', 'doctor_en',
            ]);
        });
    }
};
