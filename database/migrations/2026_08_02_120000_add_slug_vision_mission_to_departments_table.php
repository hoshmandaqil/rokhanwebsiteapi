<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
            $table->json('vision_content')->nullable()->after('description');
            $table->json('mission_content')->nullable()->after('vision_content');
        });

        $departments = DB::table('departments')->select('id', 'title')->get();
        $usedSlugs = [];

        foreach ($departments as $department) {
            $title = $department->title;
            $decoded = is_string($title) ? json_decode($title, true) : null;
            $plainTitle = is_array($decoded)
                ? (string) ($decoded['en'] ?? reset($decoded) ?: '')
                : (string) $title;

            $base = Str::slug(strip_tags($plainTitle));
            if ($base === '') {
                $base = 'department-'.$department->id;
            }

            $slug = $base;
            $count = 1;
            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base.'-'.(++$count);
            }
            $usedSlugs[] = $slug;

            DB::table('departments')->where('id', $department->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn(['slug', 'vision_content', 'mission_content']);
        });
    }
};
