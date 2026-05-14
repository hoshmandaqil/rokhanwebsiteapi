<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('site_name')->default('Rokhan University');
            $table->text('site_tagline')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_phone_secondary')->nullable();
            $table->string('contact_whatsapp_url')->nullable();
            $table->text('office_hours')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('social_facebook_url')->nullable();
            $table->string('social_instagram_url')->nullable();
            $table->string('social_x_url')->nullable();
            $table->string('social_youtube_url')->nullable();
            $table->string('social_linkedin_url')->nullable();
            $table->string('social_tiktok_url')->nullable();
            $table->text('meta_default_description')->nullable();
            $table->timestamps();
        });

        $now = now();
        DB::table('website_settings')->insert([
            'id' => 1,
            'site_name' => 'Rokhan University',
            'site_tagline' => 'Building futures through education. A leading institution dedicated to academic excellence, innovation, and service to society.',
            'logo' => null,
            'favicon' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'contact_phone_secondary' => null,
            'contact_whatsapp_url' => null,
            'office_hours' => null,
            'address_line_1' => null,
            'address_line_2' => null,
            'city' => null,
            'region' => null,
            'postal_code' => null,
            'country' => null,
            'social_facebook_url' => null,
            'social_instagram_url' => null,
            'social_x_url' => null,
            'social_youtube_url' => null,
            'social_linkedin_url' => null,
            'social_tiktok_url' => null,
            'meta_default_description' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
