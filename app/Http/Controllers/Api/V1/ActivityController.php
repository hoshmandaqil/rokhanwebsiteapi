<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ActivityIndexRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    use ResolvesApiLocale;

    /**
     * List activities with optional pagination or limit.
     */
    public function index(ActivityIndexRequest $request): JsonResponse
    {
        $this->resolveLocale($request);

        $validated = $request->validated();

        $query = Activity::query()
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        $limit = $validated['limit'] ?? null;
        if ($limit !== null) {
            $items = $query->limit($limit)->get();

            return ActivityResource::collection($items)->toResponse($request);
        }

        $perPage = $validated['perPage'] ?? 9;
        $paginated = $query->paginate($perPage);

        return ActivityResource::collection($paginated)->toResponse($request);
    }

    /**
     * Show a single activity item by slug.
     */
    public function show(Request $request, Activity $activity): JsonResponse
    {
        $this->resolveLocale($request);

        return (new ActivityResource($activity))->toResponse($request);
    }
}
