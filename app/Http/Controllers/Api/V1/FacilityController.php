<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    /**
     * Resolve the API locale from query param or Accept-Language header.
     */
    private function resolveLocale(Request $request): string
    {
        $locale = $request->query('locale');

        if (filled($locale)) {
            $request->attributes->set('api_locale', $locale);

            return $locale;
        }

        $acceptLanguage = $request->header('Accept-Language');
        if (filled($acceptLanguage) && preg_match('/^([a-z]{2}(?:-[A-Z]{2})?)/i', $acceptLanguage, $matches)) {
            $locale = str_replace('-', '_', $matches[1]);
            $request->attributes->set('api_locale', $locale);

            return $locale;
        }

        $locale = config('app.fallback_locale', 'en');
        $request->attributes->set('api_locale', $locale);

        return $locale;
    }

    /**
     * List facilities.
     */
    public function index(Request $request): JsonResponse
    {
        $this->resolveLocale($request);

        $facilities = Facility::query()
            ->orderBy('created_at')
            ->get();

        return FacilityResource::collection($facilities)->toResponse($request);
    }

    /**
     * Show a single facility by slug.
     */
    public function show(Request $request, Facility $facility): JsonResponse
    {
        $this->resolveLocale($request);

        return (new FacilityResource($facility))->toResponse($request);
    }
}
