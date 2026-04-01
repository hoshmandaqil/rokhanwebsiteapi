<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EventIndexRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use ResolvesApiLocale;

    /**
     * List published events with optional pagination, limit, and type.
     */
    public function index(EventIndexRequest $request): JsonResponse
    {
        $this->resolveLocale($request);

        $validated = $request->validated();

        $query = Event::query()
            ->published()
            ->when(
                filled($validated['type'] ?? null),
                fn ($builder) => $builder->where('type', $validated['type'])
            )
            ->orderBy('start_at');

        $limit = $validated['limit'] ?? null;
        if ($limit !== null) {
            $items = $query->limit($limit)->get();

            return EventResource::collection($items)->toResponse($request);
        }

        $perPage = $validated['perPage'] ?? 9;
        $paginated = $query->paginate($perPage);

        return EventResource::collection($paginated)->toResponse($request);
    }

    /**
     * Show a single published event by id.
     */
    public function show(Request $request, int $event): JsonResponse
    {
        $this->resolveLocale($request);

        $record = Event::query()
            ->published()
            ->findOrFail($event);

        return (new EventResource($record))->toResponse($request);
    }
}
