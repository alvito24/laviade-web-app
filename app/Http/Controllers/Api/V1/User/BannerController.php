<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Services\CampaignService;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    public function __construct(
        protected CampaignService $campaignService
    ) {
    }

    /**
     * Get active banners for homepage slider
     * Used by Flutter home_service.dart
     */
    public function index(): JsonResponse
    {
        $banners = $this->campaignService->getActiveBanners();

        // Transform for Flutter compatibility
        $data = $banners->map(function ($banner) {
            return [
                'id' => $banner->id,
                'title' => $banner->title,
                'subtitle' => $banner->subtitle,
                'image_url' => $banner->image_url,
                'mobile_image_url' => $banner->mobile_image_url ?? $banner->image_url,
                'cta_text' => $banner->cta_text,
                'cta_link' => $banner->cta_link,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
