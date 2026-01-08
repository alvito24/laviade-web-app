<x-layouts.admin title="Edit Campaign">
    <div class="max-w-3xl">
        <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Campaign Information</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Campaign Name *</label>
                        <input type="text" name="name" value="{{ old('name', $campaign->name) }}" required
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Type *</label>
                        <select name="type" required class="w-full px-3 py-2 border rounded-lg focus:outline-none">
                            <option value="hero_slider" {{ $campaign->type === 'hero_slider' ? 'selected' : '' }}>Hero
                                Slider</option>
                            <option value="banner" {{ $campaign->type === 'banner' ? 'selected' : '' }}>Banner</option>
                            <option value="promotion" {{ $campaign->type === 'promotion' ? 'selected' : '' }}>Promotion
                            </option>
                            <option value="collection" {{ $campaign->type === 'collection' ? 'selected' : '' }}>Collection
                            </option>
                            <option value="flash_sale" {{ $campaign->type === 'flash_sale' ? 'selected' : '' }}>Flash Sale
                            </option>
                            <option value="sale" {{ $campaign->type === 'sale' ? 'selected' : '' }}>Sale</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">{{ old('description', $campaign->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Start Date</label>
                            <input type="date" name="start_date"
                                value="{{ old('start_date', $campaign->start_date?->format('Y-m-d')) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">End Date</label>
                            <input type="date" name="end_date"
                                value="{{ old('end_date', $campaign->end_date?->format('Y-m-d')) }}"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                        </div>
                    </div>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $campaign->is_active ? 'checked' : '' }}>
                        <span>Active</span>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Current Banners</h2>

                @if($campaign->banners->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        @foreach($campaign->banners as $banner)
                            <div class="relative group">
                                <img src="{{ $banner->image_url }}" class="w-full aspect-video object-cover rounded-lg">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                    <button type="button" onclick="deleteBanner({{ $banner->id }})"
                                        class="text-white text-sm px-3 py-1 bg-red-600 rounded">Delete</button>
                                </div>
                                @if($banner->title)
                                    <div class="mt-1 text-sm truncate">{{ $banner->title }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <h3 class="font-medium mb-3">Add New Banners</h3>
                <div id="banners-container"></div>
                <button type="button" onclick="addBanner()" class="text-blue-600 hover:underline text-sm">
                    + Add Banner
                </button>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Update
                    Campaign</button>
                <a href="{{ route('admin.campaigns.index') }}"
                    class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</a>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let bannerIndex = 0;
            function addBanner() {
                const container = document.getElementById('banners-container');
                const html = `
                    <div class="banner-item border rounded-lg p-4 mb-4">
                        <div class="flex justify-end mb-2">
                            <button type="button" onclick="this.closest('.banner-item').remove()" class="text-red-600 text-sm">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Banner Image *</label>
                                <input type="file" name="banners[${bannerIndex}][image]" accept="image/*" required class="w-full">
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

            function deleteBanner(bannerId) {
                if (!confirm('Delete this banner?')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/campaigns/banners/${bannerId}`;
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