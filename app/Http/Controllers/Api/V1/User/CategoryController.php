<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::active()
            ->parent()
            ->with([
                'children' => function ($query) {
                    $query->active()->ordered();
                }
            ])
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
