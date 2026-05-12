<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('level', 32);
            $table->string('slug')->unique();
            $table->json('title');
            $table->json('description');
            $table->string('duration')->nullable();
            $table->json('degree')->nullable();
            $table->string('cover')->nullable();
            $table->string('thumbnail')->nullable();
            $table->json('highlights')->nullable();
            $table->json('vision_mission')->nullable();
            $table->json('academic_structure')->nullable();
            $table->json('fee_structure')->nullable();
            $table->json('faculty_profiles')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['level', 'is_published', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
