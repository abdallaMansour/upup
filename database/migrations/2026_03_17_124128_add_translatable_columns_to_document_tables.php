<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // user_achievements
        Schema::table('user_achievements', function (Blueprint $table) {
            $table->string('title_ar')->nullable()->after('type');
            $table->string('title_en')->nullable()->after('title_ar');
            $table->string('place_ar')->nullable()->after('title_en');
            $table->string('place_en')->nullable()->after('place_ar');
            $table->string('academic_year_ar')->nullable()->after('place_en');
            $table->string('academic_year_en')->nullable()->after('academic_year_ar');
            $table->string('school_ar')->nullable()->after('academic_year_en');
            $table->string('school_en')->nullable()->after('school_ar');
        });

        DB::table('user_achievements')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('user_achievements')->where('id', $row->id)->update([
                    'title_ar' => $row->title,
                    'title_en' => $row->title,
                    'place_ar' => $row->place,
                    'place_en' => $row->place,
                    'academic_year_ar' => $row->academic_year,
                    'academic_year_en' => $row->academic_year,
                    'school_ar' => $row->school,
                    'school_en' => $row->school,
                ]);
            }
        });

        Schema::table('user_achievements', function (Blueprint $table) {
            $table->dropColumn(['title', 'place', 'academic_year', 'school']);
        });

        // user_voices, user_visits, user_drawings, user_injuries, user_other_events
        $tables = ['user_voices', 'user_visits', 'user_drawings', 'user_injuries', 'user_other_events'];
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('title_ar')->nullable()->after('user_childhood_stage_id');
                $table->string('title_en')->nullable()->after('title_ar');
                $table->text('other_info_ar')->nullable()->after('title_en');
                $table->text('other_info_en')->nullable()->after('other_info_ar');
            });
        }

        foreach ($tables as $tbl) {
            DB::table($tbl)->orderBy('id')->chunk(100, function ($rows) use ($tbl) {
                foreach ($rows as $row) {
                    DB::table($tbl)->where('id', $row->id)->update([
                        'title_ar' => $row->title ?? null,
                        'title_en' => $row->title ?? null,
                        'other_info_ar' => $row->other_info ?? null,
                        'other_info_en' => $row->other_info ?? null,
                    ]);
                }
            });
        }

        foreach ($tables as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->dropColumn(['title', 'other_info']);
            });
        }
    }

    public function down(): void
    {
        // user_achievements
        Schema::table('user_achievements', function (Blueprint $table) {
            $table->string('title')->nullable()->after('type');
            $table->string('place')->nullable()->after('title');
            $table->string('academic_year')->nullable()->after('place');
            $table->string('school')->nullable()->after('academic_year');
        });

        DB::table('user_achievements')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                DB::table('user_achievements')->where('id', $row->id)->update([
                    'title' => $row->title_ar ?? $row->title_en,
                    'place' => $row->place_ar ?? $row->place_en,
                    'academic_year' => $row->academic_year_ar ?? $row->academic_year_en,
                    'school' => $row->school_ar ?? $row->school_en,
                ]);
            }
        });

        Schema::table('user_achievements', function (Blueprint $table) {
            $table->dropColumn([
                'title_ar', 'title_en', 'place_ar', 'place_en',
                'academic_year_ar', 'academic_year_en', 'school_ar', 'school_en',
            ]);
        });

        // Other tables
        $tables = ['user_voices', 'user_visits', 'user_drawings', 'user_injuries', 'user_other_events'];
        foreach ($tables as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->string('title')->nullable()->after('user_childhood_stage_id');
                $table->text('other_info')->nullable()->after('title');
            });
        }

        foreach ($tables as $tbl) {
            DB::table($tbl)->orderBy('id')->chunk(100, function ($rows) use ($tbl) {
                foreach ($rows as $row) {
                    DB::table($tbl)->where('id', $row->id)->update([
                        'title' => $row->title_ar ?? $row->title_en,
                        'other_info' => $row->other_info_ar ?? $row->other_info_en,
                    ]);
                }
            });
        }

        foreach ($tables as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {
                $table->dropColumn(['title_ar', 'title_en', 'other_info_ar', 'other_info_en']);
            });
        }
    }
};
