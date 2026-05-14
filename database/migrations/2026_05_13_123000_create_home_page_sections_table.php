<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_key')->unique();
            $table->json('heading')->nullable();
            $table->json('subheading')->nullable();
            $table->json('description')->nullable();
            $table->json('body_primary')->nullable();
            $table->json('body_secondary')->nullable();
            $table->string('video_embed_url')->nullable();
            $table->string('image')->nullable();
            $table->string('image_one')->nullable();
            $table->string('image_two')->nullable();
            $table->string('image_three')->nullable();
            $table->string('image_four')->nullable();
            $table->json('stat_title')->nullable();
            $table->json('stat_one_label')->nullable();
            $table->string('stat_one_value')->nullable();
            $table->json('stat_two_label')->nullable();
            $table->string('stat_two_value')->nullable();
            $table->json('stat_three_label')->nullable();
            $table->string('stat_three_value')->nullable();
            $table->json('stat_four_label')->nullable();
            $table->string('stat_four_value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_sections');
    }
};
