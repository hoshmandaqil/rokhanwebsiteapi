<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\NewsIndexRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use ResolvesApiLocale;

    /**
     * List news with optional pagination or limit.
     */
    public function index(NewsIndexRequest $request): JsonResponse
    {
        $this->resolveLocale($request);

        $validated = $request->validated();

        $query = News::query()
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        $limit = $validated['limit'] ?? null;
        if ($limit !== null) {
            $items = $query->limit($limit)->get();

            return NewsResource::collection($items)->toResponse($request);
        }

        $perPage = $validated['perPage'] ?? 9;
        $paginated = $query->paginate($perPage);

        return NewsResource::collection($paginated)->toResponse($request);
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
