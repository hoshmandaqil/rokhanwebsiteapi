<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HomePageSectionKey;
use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Resources\HomePageSectionResource;
use App\Models\HomePageSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    use ResolvesApiLocale;

    public function index(Request $request): JsonResponse
    {
        $this->resolveLocale($request);

        $sections = HomePageSection::query()
            ->whereIn('section_key', array_map(fn (HomePageSectionKey $key): string => $key->value, HomePageSectionKey::cases()))
            ->get()
            ->keyBy('section_key');

        return response()->json([
            'data' => [
                'top_banner' => isset($sections[HomePageSectionKey::TopBanner->value]) ? new HomePageSectionResource($sections[HomePageSectionKey::TopBanner->value]) : null,
                'video' => isset($sections[HomePageSectionKey::Video->value]) ? new HomePageSectionResource($sections[HomePageSectionKey::Video->value]) : null,
                'about_us' => isset($sections[HomePageSectionKey::AboutUs->value]) ? new HomePageSectionResource($sections[HomePageSectionKey::AboutUs->value]) : null,
            ],
        ]);
    }
}
