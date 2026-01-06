<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this product for this order
        $exists = Review::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->where('order_id', $request->order_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memberikan review untuk produk ini',
            ], 422);
        }

        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_verified_purchase' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review berhasil ditambahkan',
            'data' => $review,
        ], 201);
    }
}
