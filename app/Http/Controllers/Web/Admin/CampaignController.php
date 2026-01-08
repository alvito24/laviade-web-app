<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::with('banners')
            ->withCount('banners')
            ->latest()
            ->paginate(20);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:hero_slider,promotion,collection,flash_sale,banner,sale',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'sort_order' => 'integer|min:0',
            'banners' => 'nullable|array',
            'banners.*.image' => 'nullable|image|max:4096',
            'banners.*.title' => 'nullable|string|max:255',
            'banners.*.subtitle' => 'nullable|string|max:500',
            'banners.*.cta_text' => 'nullable|string|max:50',
            'banners.*.cta_link' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        unset($validated['banners']);
        $campaign = Campaign::create($validated);

        // Handle banners
        if ($request->has('banners')) {
            foreach ($request->banners as $index => $bannerData) {
                if (isset($bannerData['image'])) {
                    CampaignBanner::create([
                        'campaign_id' => $campaign->id,
                        'title' => $bannerData['title'] ?? null,
                        'subtitle' => $bannerData['subtitle'] ?? null,
                        'image' => $bannerData['image']->store('campaigns', 'public'),
                        'cta_text' => $bannerData['cta_text'] ?? null,
                        'cta_link' => $bannerData['cta_link'] ?? null,
                        'is_active' => true,
                        'sort_order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil dibuat');
    }

    public function edit(Campaign $campaign): View
    {
        $campaign->load('banners');
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:hero_slider,promotion,collection,flash_sale,banner,sale',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'sort_order' => 'integer|min:0',
        ]);

        if ($campaign->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $campaign->update($validated);

        return back()->with('success', 'Campaign berhasil diperbarui');
    }

    public function destroy(Campaign $campaign)
    {
        foreach ($campaign->banners as $banner) {
            Storage::disk('public')->delete($banner->image);
            if ($banner->image_mobile) {
                Storage::disk('public')->delete($banner->image_mobile);
            }
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil dihapus');
    }

    public function storeBanner(Request $request, Campaign $campaign)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'image' => 'required|image|max:4096',
            'image_mobile' => 'nullable|image|max:2048',
            'cta_text' => 'nullable|string|max:50',
            'cta_link' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['title', 'subtitle', 'cta_text', 'cta_link']);
        $data['campaign_id'] = $campaign->id;
        $data['image'] = $request->file('image')->store('campaigns', 'public');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $campaign->banners()->max('sort_order') + 1;

        if ($request->hasFile('image_mobile')) {
            $data['image_mobile'] = $request->file('image_mobile')->store('campaigns', 'public');
        }

        CampaignBanner::create($data);

        return back()->with('success', 'Banner berhasil ditambahkan');
    }

    public function deleteBanner(CampaignBanner $banner)
    {
        Storage::disk('public')->delete($banner->image);
        if ($banner->image_mobile) {
            Storage::disk('public')->delete($banner->image_mobile);
        }

        $banner->delete();

        return back()->with('success', 'Banner berhasil dihapus');
    }
}
