<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
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
     * List news with optional pagination or limit.
     */
    public function index(Request $request): JsonResponse
    {
        $this->resolveLocale($request);

        $query = News::query()
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        $limit = (int) $request->query('limit', 0);
        if ($limit > 0) {
            $items = $query->limit($limit)->get();

            return NewsResource::collection($items)->response();
        }

        $perPage = max(1, (int) $request->query('perPage', 9));
        $paginated = $query->paginate($perPage);

        return NewsResource::collection($paginated)->response();
    }

    /**
     * Show a single news item by slug.
     */
    public function show(Request $request, News $news): JsonResponse
    {
        $this->resolveLocale($request);

        return (new NewsResource($news))->toResponse($request);
    }
}
