<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicWebsiteSettingResource;
use App\Models\WebsiteSetting;
use Illuminate\Http\JsonResponse;

class WebsiteSettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = WebsiteSetting::singleton();

        return new PublicWebsiteSettingResource($settings);
    }
}
