<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'primaryImage']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $products = $query->latest()->paginate(20);
        $categories = Category::active()->ordered()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sizes' => 'nullable',
            'colors' => 'nullable',
            'material' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'images.*' => 'image|max:10000',
        ]);

        // Generate Unique Slug
        $originalSlug = Str::slug($validated['name']);
        $slug = $originalSlug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        $validated['slug'] = $slug;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new_arrival'] = $request->boolean('is_new_arrival');
        $validated['is_best_seller'] = $request->boolean('is_best_seller');

        // Handle sizes - accept JSON string or array
        if (!empty($validated['sizes'])) {
            if (is_string($validated['sizes'])) {
                $decoded = json_decode($validated['sizes'], true);
                $validated['sizes'] = $decoded ?: array_filter(array_map('trim', explode(',', $validated['sizes'])));
            }
        } else {
            $validated['sizes'] = null;
        }

        // Handle colors - accept JSON string or array
        if (!empty($validated['colors'])) {
            if (is_string($validated['colors'])) {
                $decoded = json_decode($validated['colors'], true);
                $validated['colors'] = $decoded ?: array_filter(array_map('trim', explode(',', $validated['colors'])));
            }
        } else {
            $validated['colors'] = null;
        }

        $product = Product::create($validated);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Product $product): View
    {
        $categories = Category::active()->ordered()->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sizes' => 'nullable',
            'colors' => 'nullable',
            'material' => 'nullable|string|max:100',
            'weight' => 'nullable|numeric|min:0',
            'images.*' => 'image|max:10000',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_new_arrival'] = $request->boolean('is_new_arrival');
        $validated['is_best_seller'] = $request->boolean('is_best_seller');

        if ($product->name !== $validated['name']) {
            // Generate Unique Slug
            $originalSlug = Str::slug($validated['name']);
            $slug = $originalSlug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            $validated['slug'] = $slug;
        }

        // Handle sizes - accept JSON string or array
        if (!empty($validated['sizes'])) {
            if (is_string($validated['sizes'])) {
                $decoded = json_decode($validated['sizes'], true);
                $validated['sizes'] = $decoded ?: array_filter(array_map('trim', explode(',', $validated['sizes'])));
            }
        } else {
            $validated['sizes'] = null;
        }

        // Handle colors - accept JSON string or array
        if (!empty($validated['colors'])) {
            if (is_string($validated['colors'])) {
                $decoded = json_decode($validated['colors'], true);
                $validated['colors'] = $decoded ?: array_filter(array_map('trim', explode(',', $validated['colors'])));
            }
        } else {
            $validated['colors'] = null;
        }

        $product->update($validated);

        // Handle new images
        if ($request->hasFile('images')) {
            $lastOrder = $product->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                    'sort_order' => $lastOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Product $product)
    {
        // Delete images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus');
    }

    public function deleteImage(ProductImage $image)
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();

        return back()->with('success', 'Gambar berhasil dihapus');
    }

    public function setPrimaryImage(ProductImage $image)
    {
        ProductImage::where('product_id', $image->product_id)
            ->update(['is_primary' => false]);

        $image->update(['is_primary' => true]);

        return back()->with('success', 'Gambar utama berhasil diubah');
    }
}
