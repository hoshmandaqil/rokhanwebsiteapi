<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    /**
     * Display the about page with content grouped by type (University, History, Program, Department).
     * Each item has title, description and cover (image).
     */
    public function __invoke(Request $request): Response
    {
        $abouts = About::query()
            ->orderBy('type')
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn (About $about) => $about->type->value);

        $groups = collect(\App\Enums\AboutType::cases())
            ->mapWithKeys(fn ($type) => [$type->value => $type->label()])
            ->all();

        $groupedAbouts = [];
        foreach ($groups as $key => $label) {
            $items = $abouts->get($key, collect())->map(fn (About $about) => [
                'id' => $about->id,
                'title' => $about->title,
                'description' => $about->description,
                'cover' => $about->image ? \Illuminate\Support\Facades\Storage::url($about->image) : null,
            ])->values()->all();
            $groupedAbouts[] = [
                'key' => $key,
                'label' => $label,
                'items' => $items,
            ];
        }

        return Inertia::render('About', [
            'groups' => $groupedAbouts,
        ]);
    }
}
