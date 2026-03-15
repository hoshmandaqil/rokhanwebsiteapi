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
        Schema::create('leadership_messages', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->json('name');
            $table->json('title');
            $table->json('message');
            $table->string('image');
            $table->string('cover');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leadership_messages');
    }
};
