<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_banners', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        $rows = [
            ['slug' => 'about', 'name' => 'About page', 'sort_order' => 10],
            ['slug' => 'news', 'name' => 'News listing', 'sort_order' => 20],
            ['slug' => 'news_details', 'name' => 'News details', 'sort_order' => 25],
            ['slug' => 'announcements', 'name' => 'Announcements listing', 'sort_order' => 30],
            ['slug' => 'events', 'name' => 'Events listing', 'sort_order' => 40],
            ['slug' => 'activities', 'name' => 'Activities listing', 'sort_order' => 50],
            ['slug' => 'academics', 'name' => 'Academics hub', 'sort_order' => 60],
            ['slug' => 'students', 'name' => 'Students hub', 'sort_order' => 70],
            ['slug' => 'students_registration', 'name' => 'Student registration', 'sort_order' => 80],
            ['slug' => 'students_admission', 'name' => 'Admission', 'sort_order' => 90],
            ['slug' => 'students_academic_calendar', 'name' => 'Academic calendar', 'sort_order' => 100],
            ['slug' => 'students_student_life', 'name' => 'Student life', 'sort_order' => 110],
            ['slug' => 'students_student_support', 'name' => 'Student support', 'sort_order' => 120],
            ['slug' => 'research', 'name' => 'Research hub', 'sort_order' => 130],
            ['slug' => 'quality_assurance', 'name' => 'Quality assurance hub', 'sort_order' => 140],
            ['slug' => 'facilities', 'name' => 'Facilities listing', 'sort_order' => 150],
            ['slug' => 'faculties', 'name' => 'Faculties listing', 'sort_order' => 160],
            ['slug' => 'careers', 'name' => 'Careers listing', 'sort_order' => 170],
            ['slug' => 'programs_bachelor', 'name' => 'Bachelor programs listing', 'sort_order' => 180],
            ['slug' => 'programs_master', 'name' => 'Master programs listing', 'sort_order' => 190],
            ['slug' => 'programs_del', 'name' => 'DEL programs listing', 'sort_order' => 200],
            ['slug' => 'programs_dit', 'name' => 'DIT programs listing', 'sort_order' => 210],
        ];

        foreach ($rows as $row) {
            DB::table('website_banners')->insert([
                'slug' => $row['slug'],
                'name' => $row['name'],
                'image' => null,
                'sort_order' => $row['sort_order'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('website_banners');
    }
};
