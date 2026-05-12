<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table): void {
            if (Schema::hasColumn('programs', 'vision_mission')) {
                $table->dropColumn([
                    'vision_mission',
                    'academic_structure',
                    'fee_structure',
                    'faculty_profiles',
                ]);
            }
        });

        Schema::table('programs', function (Blueprint $table): void {
            if (! Schema::hasColumn('programs', 'vision_content')) {
                $table->json('vision_content')->nullable()->after('highlights');
            }
            if (! Schema::hasColumn('programs', 'mission_content')) {
                $table->json('mission_content')->nullable()->after('vision_content');
            }
            if (! Schema::hasColumn('programs', 'program_values')) {
                $table->json('program_values')->nullable()->after('mission_content');
            }
            if (! Schema::hasColumn('programs', 'academic_structure_html')) {
                $table->json('academic_structure_html')->nullable()->after('program_values');
            }
            if (! Schema::hasColumn('programs', 'fee_structure_html')) {
                $table->json('fee_structure_html')->nullable()->after('academic_structure_html');
            }
        });

        Schema::table('faculties', function (Blueprint $table): void {
            if (Schema::hasColumn('faculties', 'overview_content')) {
                $table->dropColumn([
                    'overview_content',
                    'overview_files',
                    'academic_structure_content',
                    'fee_structure_content',
                ]);
            }
        });

        Schema::table('faculties', function (Blueprint $table): void {
            if (! Schema::hasColumn('faculties', 'dean_message')) {
                $table->json('dean_message')->nullable()->after('description');
            }
            if (! Schema::hasColumn('faculties', 'faculty_profile_instructor_ids')) {
                $table->json('faculty_profile_instructor_ids')->nullable()->after('dean_message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table): void {
            if (Schema::hasColumn('faculties', 'dean_message')) {
                $table->dropColumn(['dean_message', 'faculty_profile_instructor_ids']);
            }
        });

        Schema::table('faculties', function (Blueprint $table): void {
            $table->json('overview_content')->nullable();
            $table->json('overview_files')->nullable();
            $table->json('academic_structure_content')->nullable();
            $table->json('fee_structure_content')->nullable();
        });

        Schema::table('programs', function (Blueprint $table): void {
            if (Schema::hasColumn('programs', 'vision_content')) {
                $table->dropColumn([
                    'vision_content',
                    'mission_content',
                    'program_values',
                    'academic_structure_html',
                    'fee_structure_html',
                ]);
            }
        });

        Schema::table('programs', function (Blueprint $table): void {
            $table->json('vision_mission')->nullable();
            $table->json('academic_structure')->nullable();
            $table->json('fee_structure')->nullable();
            $table->json('faculty_profiles')->nullable();
        });
    }
};
