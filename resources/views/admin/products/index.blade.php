<x-layouts.admin title="Products">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div class="w-full md:w-auto">
            <form class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                    class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black w-full md:w-64">
                <select name="category_id" class="px-4 py-2 border rounded-lg focus:outline-none w-full md:w-auto">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 w-full md:w-auto">Filter</button>
            </form>
        </div>
        <a href="{{ route('admin.products.create') }}"
            class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 w-full md:w-auto text-center">
            + Add Product
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($products as $product)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->primary_image_url }}" class="w-12 h-12 rounded object-cover">
                                <div>
                                    <div class="font-medium">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">{{ $product->category->name }}</td>
                        <td class="px-6 py-4">
                            <div>{{ $product->formatted_current_price }}</div>
                            @if($product->hasDiscount())
                                <div class="text-sm text-gray-400 line-through">{{ $product->formatted_price }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="{{ $product->stock < 10 ? 'text-red-600' : '' }}">{{ $product->stock }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No products found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->withQueryString()->links() }}
    </div>
</x-layouts.admin>