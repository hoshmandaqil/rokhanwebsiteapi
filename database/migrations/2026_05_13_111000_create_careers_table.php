<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug')->unique();
            $table->json('department');
            $table->json('location');
            $table->json('employment_type');
            $table->date('deadline')->nullable();
            $table->json('description')->nullable();
            $table->json('responsibilities')->nullable();
            $table->json('qualifications')->nullable();
            $table->json('submission_guidelines')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
