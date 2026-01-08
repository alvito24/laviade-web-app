<x-layouts.admin title="Edit Product">
    <div class="max-w-4xl">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Product Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Category *</label>
                        <select name="category_id" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Short Description</label>
                    <input type="text" name="short_description"
                        value="{{ old('short_description', $product->short_description) }}"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="4"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Pricing & Stock</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Price (Rp) *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="0"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Sale Price (Rp)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}"
                            min="0"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Stock *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Current Images</h2>
                <div class="grid grid-cols-4 gap-4 mb-4">
                    @foreach($product->images as $image)
                        <div class="relative group">
                            <img src="{{ $image->image_url }}"
                                class="w-full aspect-square object-cover rounded-lg {{ $image->is_primary ? 'ring-2 ring-black' : '' }}">
                            <div
                                class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-2">
                                @if(!$image->is_primary)
                                    <button type="button" onclick="setImagePrimary({{ $image->id }})"
                                        class="text-white text-xs px-2 py-1 bg-blue-600 rounded">Primary</button>
                                @endif
                                <button type="button" onclick="deleteImage({{ $image->id }})"
                                    class="text-white text-xs px-2 py-1 bg-red-600 rounded">Delete</button>
                            </div>
                            @if($image->is_primary)
                                <span class="absolute top-1 left-1 text-xs bg-black text-white px-1 rounded">Primary</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <label class="block text-sm font-medium mb-1">Add More Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full">
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Variants</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Sizes (comma separated)</label>
                        <input type="text" name="sizes"
                            value="{{ old('sizes', $product->sizes ? implode(', ', $product->sizes) : '') }}"
                            placeholder="S, M, L, XL"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Colors (comma separated)</label>
                        <input type="text" name="colors"
                            value="{{ old('colors', $product->colors ? implode(', ', $product->colors) : '') }}"
                            placeholder="Black, White, Grey"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Material</label>
                        <input type="text" name="material" value="{{ old('material', $product->material) }}"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Weight (grams)</label>
                        <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" min="0"
                            step="0.01"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Status</h2>
                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}
                            class="mr-2">
                        <span>Active</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}
                            class="mr-2">
                        <span>Featured</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_new_arrival" value="1" {{ $product->is_new_arrival ? 'checked' : '' }} class="mr-2">
                        <span>New Arrival</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_best_seller" value="1" {{ $product->is_best_seller ? 'checked' : '' }} class="mr-2">
                        <span>Best Seller</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Update
                    Product</button>
                <a href="{{ route('admin.products.index') }}"
                    class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function setImagePrimary(imageId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/products/image/${imageId}/primary`;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
                document.body.appendChild(form);
                form.submit();
            }

            function deleteImage(imageId) {
                if (!confirm('Delete this image?')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/products/image/${imageId}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        </script>
    @endpush
</x-layouts.admin>