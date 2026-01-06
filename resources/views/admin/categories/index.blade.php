<x-layouts.admin title="Categories">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold">All Categories</h2>
        <button onclick="openModal()" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">
            + Add Category
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($category->image)
                                    <img src="{{ $category->image_url }}" class="w-10 h-10 rounded object-cover">
                                @else
                                    <div class="w-10 h-10 rounded bg-gray-200 flex items-center justify-center text-gray-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $category->name }}</div>
                                    @if($category->parent)
                                        <div class="text-xs text-gray-500">Parent: {{ $category->parent->name }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4">{{ $category->products_count ?? 0 }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <button onclick="editCategory({{ json_encode($category) }})"
                                    class="text-blue-600 hover:underline">Edit</button>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No categories found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="category-modal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full">
            <div class="p-6">
                <h3 id="modal-title" class="text-lg font-bold mb-4">Add Category</h3>
                <form id="category-form" method="POST" action="{{ route('admin.categories.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div id="method-field"></div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Name *</label>
                            <input type="text" name="name" id="inp-name" required
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Parent Category</label>
                            <select name="parent_id" id="inp-parent_id"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                                <option value="">None (Top Level)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="description" id="inp-description" rows="3"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Image</label>
                            <input type="file" name="image" accept="image/*" class="w-full">
                        </div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" id="inp-is_active" value="1" checked>
                            <span class="text-sm">Active</span>
                        </label>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeModal()"
                            class="flex-1 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</button>
                        <button type="submit"
                            class="flex-1 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal-title').textContent = 'Add Category';
            document.getElementById('category-form').action = '{{ route("admin.categories.store") }}';
            document.getElementById('method-field').innerHTML = '';
            document.getElementById('category-form').reset();
            document.getElementById('inp-is_active').checked = true;
            document.getElementById('category-modal').classList.remove('hidden');
        }

        function editCategory(category) {
            document.getElementById('modal-title').textContent = 'Edit Category';
            document.getElementById('category-form').action = `/admin/categories/${category.id}`;
            document.getElementById('method-field').innerHTML = '@method("PUT")';
            document.getElementById('inp-name').value = category.name;
            document.getElementById('inp-parent_id').value = category.parent_id || '';
            document.getElementById('inp-description').value = category.description || '';
            document.getElementById('inp-is_active').checked = category.is_active;
            document.getElementById('category-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('category-modal').classList.add('hidden');
        }
    </script>
</x-layouts.admin>