<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignBanner;
use Illuminate\Database\Eloquent\Collection;

class CampaignService
{
    public function getActiveHeroSliders(): Collection
    {
        return Campaign::with(['banners' => fn($q) => $q->active()->orderBy('sort_order')])
            ->active()
            ->heroSlider()
            ->orderBy('sort_order')
            ->get();
    }

    public function getActiveBanners(): Collection
    {
        return CampaignBanner::whereHas('campaign', function ($q) {
            $q->active()->heroSlider();
        })
            ->active()
            ->orderBy('sort_order')
            ->get();
    }

    public function getActiveCampaigns(): Collection
    {
        return Campaign::with('banners')
            ->active()
            ->orderBy('sort_order')
            ->get();
    }

    public function getCampaignBySlug(string $slug): ?Campaign
    {
        return Campaign::with('banners')
            ->active()
            ->where('slug', $slug)
            ->first();
    }
}
