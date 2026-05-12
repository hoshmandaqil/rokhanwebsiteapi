<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ProgramIndexRequest;
use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    use ResolvesApiLocale;

    /**
     * List published programs. Optional filters: ?level=bachelor|master|del|dit, ?faculty_id=
     */
    public function index(ProgramIndexRequest $request): JsonResponse
    {
        $this->resolveLocale($request);

        $validated = $request->validated();

        $query = Program::query()
            ->with(['department'])
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('id');

        if (! empty($validated['level'])) {
            $query->where('level', $validated['level']);
        }

        if (! empty($validated['faculty_id'])) {
            $query->whereHas('department', fn ($q) => $q->where('faculty_id', $validated['faculty_id']));
        }

        $items = $query->get();

        return ProgramResource::collection($items)->toResponse($request);
    }

    /**
     * Show a single published program by slug or numeric id.
     */
    public function show(Request $request, string $identifier): JsonResponse
    {
        $this->resolveLocale($request);

        $program = Program::query()
            ->where('is_published', true)
            ->with(['department'])
            ->where(function ($q) use ($identifier): void {
                $q->where('slug', $identifier);
                if (ctype_digit((string) $identifier)) {
                    $q->orWhere('id', (int) $identifier);
                }
            })
            ->firstOrFail();

        return (new ProgramResource($program))->toResponse($request);
    }
}
