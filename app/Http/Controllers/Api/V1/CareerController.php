<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Resources\CareerResource;
use App\Models\Career;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    use ResolvesApiLocale;

    public function index(Request $request): JsonResponse
    {
        $this->resolveLocale($request);

        $query = Career::query()
            ->where('is_published', true)
            ->orderByDesc('deadline')
            ->orderByDesc('created_at');

        return CareerResource::collection($query->get())->toResponse($request);
    }

    public function show(Request $request, Career $career): JsonResponse
    {
        $this->resolveLocale($request);

        abort_unless($career->is_published, 404);

        return (new CareerResource($career))->toResponse($request);
    }
}
