<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Review;
use App\Services\OrderService;
use App\Services\WishlistService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected WishlistService $wishlistService
    ) {
    }

    public function index(): View
    {
        $user = auth()->user();
        return view('user.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'birth_date' => 'nullable|date',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $data = $request->only(['name', 'phone', 'gender', 'birth_date']);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function orders(): View
    {
        $orders = $this->orderService->getUserOrders(auth()->id(), 10);
        return view('user.profile.orders', compact('orders'));
    }

    public function orderDetail(string $orderNumber): View
    {
        $order = $this->orderService->getOrderDetail(auth()->id(), $orderNumber);

        if (!$order) {
            abort(404);
        }

        return view('user.profile.order-detail', compact('order'));
    }

    public function wishlist(): View
    {
        $wishlists = $this->wishlistService->getUserWishlist(auth()->id());
        return view('user.profile.wishlist', compact('wishlists'));
    }

    public function addresses(): View
    {
        $addresses = auth()->user()->addresses()->orderByDesc('is_primary')->get();
        return view('user.profile.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
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
            'notes' => 'nullable|string|max:255',
        ]);

        $address = auth()->user()->addresses()->create($request->all());

        if ($request->boolean('is_primary') || auth()->user()->addresses()->count() === 1) {
            $address->setPrimary();
        }

        return back()->with('success', 'Alamat berhasil ditambahkan');
    }

    public function updateAddress(Request $request, Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

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

        $address->update($request->all());

        if ($request->boolean('is_primary')) {
            $address->setPrimary();
        }

        return back()->with('success', 'Alamat berhasil diperbarui');
    }

    public function deleteAddress(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->delete();
        return back()->with('success', 'Alamat berhasil dihapus');
    }

    public function setPrimaryAddress(Address $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }

        $address->setPrimary();
        return back()->with('success', 'Alamat utama berhasil diubah');
    }
}
