<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AboutType;
use App\Http\Controllers\Api\V1\Concerns\ResolvesApiLocale;
use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AboutController extends Controller
{
    use ResolvesApiLocale;

    /**
     * List about records, optionally filtered by type.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->resolveLocale($request);

        $query = About::query()->orderBy('created_at');
        $type = $request->query('type');
        if (is_string($type) && in_array($type, array_column(AboutType::cases(), 'value'), true)) {
            $query->where('type', $type);
        }

        return AboutResource::collection($query->get());
    }

    /**
     * Show one about record by computed title slug.
     */
    public function show(Request $request, string $slug): AboutResource
    {
        $this->resolveLocale($request);

        $query = About::query()->orderBy('created_at');
        $type = $request->query('type');
        if (is_string($type) && in_array($type, array_column(AboutType::cases(), 'value'), true)) {
            $query->where('type', $type);
        }

        $record = $query->get()->first(function (About $about) use ($slug): bool {
            $locale = request()->attributes->get('api_locale', config('app.fallback_locale', 'en'));
            $fallback = config('app.fallback_locale', 'en');
            $title = $about->getTranslation('title', $locale)
                ?? $about->getTranslation('title', $fallback)
                ?? '';

            return Str::slug(is_string($title) ? $title : '') === $slug;
        });

        if (! $record) {
            throw new NotFoundHttpException('About page not found.');
        }

        return new AboutResource($record);
    }
}
