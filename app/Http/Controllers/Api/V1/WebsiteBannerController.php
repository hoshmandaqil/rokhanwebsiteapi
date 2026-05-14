<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBanner;
use App\Support\MediaUrl;
use Illuminate\Http\JsonResponse;

class WebsiteBannerController extends Controller
{
    public function index(): JsonResponse
    {
        $rows = WebsiteBanner::query()
            ->orderBy('sort_order')
            ->orderBy('slug')
            ->get()
            ->keyBy('slug');

        /** @var array<string, string|null> $data */
        $data = [];
        foreach ($rows as $slug => $row) {
            $data[$slug] = MediaUrl::toAbsolute($row->image);
        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
