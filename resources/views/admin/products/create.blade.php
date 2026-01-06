<x-layouts.admin title="Add Product">
    <div class="max-w-4xl">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Product Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Category *</label>
                        <select name="category_id" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Short Description</label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}" maxlength="500"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Pricing & Stock</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Price (Rp) *</label>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Sale Price (Rp)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price') }}" min="0"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Stock *</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Variants</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Sizes (comma separated)</label>
                        <input type="text" name="sizes" value="{{ old('sizes') }}" placeholder="S, M, L, XL"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Colors (comma separated)</label>
                        <input type="text" name="colors" value="{{ old('colors') }}" placeholder="Black, White, Grey"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Material</label>
                        <input type="text" name="material" value="{{ old('material') }}"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Weight (grams)</label>
                        <input type="number" name="weight" value="{{ old('weight') }}" min="0" step="0.01"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Images</h2>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full">
                <p class="text-sm text-gray-500 mt-2">Upload multiple images. First image will be primary.</p>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Status</h2>
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                        <span>Active</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" class="mr-2">
                        <span>Featured</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_new_arrival" value="1" class="mr-2">
                        <span>New Arrival</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_best_seller" value="1" class="mr-2">
                        <span>Best Seller</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Save
                    Product</button>
                <a href="{{ route('admin.products.index') }}"
                    class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.admin>