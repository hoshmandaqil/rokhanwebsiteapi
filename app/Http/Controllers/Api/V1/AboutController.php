<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AboutPageKey;
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

    private function findAboutBySlug(string $slug, ?string $typeValue): ?About
    {
        $byKey = About::query()->where('page_key', $slug)->first();
        if ($byKey !== null) {
            return $byKey;
        }

        $query = About::query()->orderBy('created_at');

        if ($typeValue !== null && in_array($typeValue, array_column(AboutType::cases(), 'value'), true)) {
            $query->where('type', $typeValue);
        }

        $normalizedSlug = Str::slug($slug);

        return $query->get()->first(function (About $about) use ($normalizedSlug): bool {
            $locale = request()->attributes->get('api_locale', config('app.fallback_locale', 'en'));
            $fallback = config('app.fallback_locale', 'en');
            $title = $about->getTranslation('title', $locale)
                ?? $about->getTranslation('title', $fallback)
                ?? '';

            return Str::slug(is_string($title) ? $title : '') === $normalizedSlug;
        });
    }

    /**
     * List about records (website menu pages first, then any legacy rows).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->resolveLocale($request);

        $type = $request->query('type');
        if (is_string($type) && in_array($type, array_column(AboutType::cases(), 'value'), true)) {
            $query = About::query()->orderBy('created_at')->where('type', $type);

            return AboutResource::collection($query->get());
        }

        $orderedKeys = AboutPageKey::orderedValues();
        $websiteRows = About::query()
            ->whereIn('page_key', $orderedKeys)
            ->get()
            ->sortBy(fn (About $about): int => array_search((string) $about->page_key, $orderedKeys, true) ?: 999)
            ->values();

        if ($websiteRows->isNotEmpty()) {
            return AboutResource::collection($websiteRows);
        }

        return AboutResource::collection(About::query()->orderBy('created_at')->get());
    }

    /**
     * Show one about record by page_key or legacy title slug.
     */
    public function show(Request $request, string $slug): AboutResource
    {
        $this->resolveLocale($request);

        $type = $request->query('type');
        $typeValue = is_string($type) && in_array($type, array_column(AboutType::cases(), 'value'), true)
            ? $type
            : null;

        $record = $this->findAboutBySlug($slug, $typeValue);

        if (! $record && $typeValue !== null) {
            $record = $this->findAboutBySlug($slug, null);
        }

        if (! $record) {
            throw new NotFoundHttpException('About page not found.');
        }

        return new AboutResource($record);
    }
}
