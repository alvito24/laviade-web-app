<x-layouts.admin title="Add Campaign">
    <div class="max-w-3xl">
        <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Campaign Information</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Campaign Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Type *</label>
                        <select name="type" required class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                            <option value="hero_slider">Hero Slider</option>
                            <option value="banner">Banner</option>
                            <option value="promotion">Promotion</option>
                            <option value="collection">Collection</option>
                            <option value="flash_sale">Flash Sale</option>
                            <option value="sale">Sale</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                    </div>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Active</span>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Banner Images</h2>
                <p class="text-sm text-gray-500 mb-4">Upload banner images for this campaign (optional - can be added
                    later from Edit page)</p>

                <div id="banners-container">
                    <div class="banner-item border rounded-lg p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Banner Image</label>
                                <input type="file" name="banners[0][image]" accept="image/*" class="w-full">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Title</label>
                                <input type="text" name="banners[0][title]" class="w-full px-3 py-2 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Subtitle</label>
                                <input type="text" name="banners[0][subtitle]"
                                    class="w-full px-3 py-2 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">CTA Link</label>
                                <input type="text" name="banners[0][cta_link]" placeholder="/shop"
                                    class="w-full px-3 py-2 border rounded-lg">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">CTA Text</label>
                                <input type="text" name="banners[0][cta_text]" placeholder="Shop Now"
                                    class="w-full px-3 py-2 border rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addBanner()" class="text-blue-600 hover:underline text-sm">
                    + Add Another Banner
                </button>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Create
                    Campaign</button>
                <a href="{{ route('admin.campaigns.index') }}"
                    class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        let bannerIndex = 1;
        function addBanner() {
            const container = document.getElementById('banners-container');
            const html = `
                <div class="banner-item border rounded-lg p-4 mb-4">
                    <div class="flex justify-end mb-2">
                        <button type="button" onclick="this.closest('.banner-item').remove()" class="text-red-600 text-sm">Remove</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Banner Image</label>
                            <input type="file" name="banners[${bannerIndex}][image]" accept="image/*" class="w-full">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Title</label>
                            <input type="text" name="banners[${bannerIndex}][title]" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Subtitle</label>
                            <input type="text" name="banners[${bannerIndex}][subtitle]" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">CTA Link</label>
                            <input type="text" name="banners[${bannerIndex}][cta_link]" placeholder="/shop" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">CTA Text</label>
                            <input type="text" name="banners[${bannerIndex}][cta_text]" placeholder="Shop Now" class="w-full px-3 py-2 border rounded-lg">
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            bannerIndex++;
        }
    </script>
</x-layouts.admin>