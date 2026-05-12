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
        Schema::table('faculties', function (Blueprint $table): void {
            $table->json('overview_content')->nullable()->after('description');
            $table->json('overview_files')->nullable()->after('overview_content');
            $table->json('mission_content')->nullable()->after('overview_files');
            $table->json('vision_content')->nullable()->after('mission_content');
            $table->json('academic_structure_content')->nullable()->after('vision_content');
            $table->json('fee_structure_content')->nullable()->after('academic_structure_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table): void {
            $table->dropColumn([
                'overview_content',
                'overview_files',
                'mission_content',
                'vision_content',
                'academic_structure_content',
                'fee_structure_content',
            ]);
        });
    }
};
