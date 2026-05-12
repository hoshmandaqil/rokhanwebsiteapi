<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Resources\FacultyResource;
use App\Models\Faculty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    use ResolvesApiLocale;

    /**
     * List faculties.
     */
    public function index(Request $request): JsonResponse
    {
        $this->resolveLocale($request);

        $faculties = Faculty::query()
            ->with([
                'departments.programs',
                'strategicPlans',
                'instructors',
            ])
            ->orderBy('created_at')
            ->get();

        return FacultyResource::collection($faculties)->toResponse($request);
    }

    /**
     * Show a single faculty by id.
     */
    public function show(Request $request, Faculty $faculty): JsonResponse
    {
        $this->resolveLocale($request);

        $faculty->loadMissing([
            'departments.programs',
            'strategicPlans',
            'instructors',
        ]);

        return (new FacultyResource($faculty))->toResponse($request);
    }
}
