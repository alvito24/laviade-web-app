<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\CampaignService;
use App\Services\ProductService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected ProductService $productService,
        protected CampaignService $campaignService
    ) {
    }

    public function index(): View
    {
        $banners = $this->campaignService->getActiveBanners();
        $newArrivals = $this->productService->getNewArrivals(8);
        $bestSellers = $this->productService->getBestSellers(8);

        return view('user.home', compact('banners', 'newArrivals', 'bestSellers'));
    }

    public function about(): View
    {
        return view('user.about');
    }

    public function contact(): View
    {
        return view('user.contact');
    }
}
