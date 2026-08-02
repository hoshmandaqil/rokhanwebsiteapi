<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use ResolvesApiLocale;

    /**
     * List departments. Optional filter: ?faculty_id=
     */
    public function index(Request $request): JsonResponse
    {
        $this->resolveLocale($request);

        $query = Department::query()
            ->with(['faculty', 'programs'])
            ->orderBy('id');

        if ($request->filled('faculty_id') && ctype_digit((string) $request->query('faculty_id'))) {
            $query->where('faculty_id', (int) $request->query('faculty_id'));
        }

        return DepartmentResource::collection($query->get())->toResponse($request);
    }

    /**
     * Show a single department by slug or numeric id.
     */
    public function show(Request $request, string $identifier): JsonResponse
    {
        $this->resolveLocale($request);

        $department = Department::query()
            ->with(['faculty', 'programs'])
            ->where(function ($q) use ($identifier): void {
                $q->where('slug', $identifier);
                if (ctype_digit((string) $identifier)) {
                    $q->orWhere('id', (int) $identifier);
                }
            })
            ->firstOrFail();

        return (new DepartmentResource($department))->toResponse($request);
    }
}
