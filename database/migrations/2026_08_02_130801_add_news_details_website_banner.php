<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('website_banners')->where('slug', 'news_details')->exists()) {
            return;
        }

        $now = now();

        DB::table('website_banners')->insert([
            'slug' => 'news_details',
            'name' => 'News details',
            'image' => null,
            'sort_order' => 25,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('website_banners')->where('slug', 'news_details')->delete();
    }
};
