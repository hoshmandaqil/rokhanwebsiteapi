<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityResource;
use App\Models\Facility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    use ResolvesApiLocale;

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
