<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load('addresses'),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'birth_date' => 'nullable|date',
        ]);

        $request->user()->update($request->only(['name', 'phone', 'gender', 'birth_date']));

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $request->user()->fresh(),
        ]);
    }

    public function addresses(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->addresses,
        ]);
    }

    public function storeAddress(Request $request): JsonResponse
    {
        $request->validate([
            'label' => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'address_detail' => 'required|string',
        ]);

        $address = $request->user()->addresses()->create($request->all());

        if ($request->boolean('is_primary')) {
            $address->setPrimary();
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan',
            'data' => $address,
        ], 201);
    }

    public function updateAddress(Request $request, Address $address): JsonResponse
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $address->update($request->all());

        if ($request->boolean('is_primary')) {
            $address->setPrimary();
        }

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil diperbarui',
            'data' => $address->fresh(),
        ]);
    }

    public function deleteAddress(Request $request, Address $address): JsonResponse
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil dihapus',
        ]);
    }
}
