<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AnnouncementIndexRequest;
use App\Http\Resources\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    use ResolvesApiLocale;

    /**
     * List announcements with optional pagination or limit.
     */
    public function index(AnnouncementIndexRequest $request): JsonResponse
    {
        $this->resolveLocale($request);

        $validated = $request->validated();

        $query = Announcement::query()
            ->orderByDesc('date')
            ->orderByDesc('created_at');

        $limit = $validated['limit'] ?? null;
        if ($limit !== null) {
            $items = $query->limit($limit)->get();

            return AnnouncementResource::collection($items)->toResponse($request);
        }

        $perPage = $validated['perPage'] ?? 9;
        $paginated = $query->paginate($perPage);

        return AnnouncementResource::collection($paginated)->toResponse($request);
    }

    /**
     * Show a single announcement item by slug.
     */
    public function show(Request $request, Announcement $announcement): JsonResponse
    {
        $this->resolveLocale($request);

        return (new AnnouncementResource($announcement))->toResponse($request);
    }
}
