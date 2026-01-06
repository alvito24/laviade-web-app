<x-layouts.admin title="Campaigns">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold">All Campaigns</h2>
        <a href="{{ route('admin.campaigns.create') }}"
            class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">
            + Add Campaign
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Campaign</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Banners</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($campaigns as $campaign)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="font-medium">{{ $campaign->name }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($campaign->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded bg-gray-100">
                                {{ ucfirst(str_replace('_', ' ', $campaign->type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div>{{ $campaign->start_date?->format('d M Y') ?? '-' }}</div>
                            <div class="text-gray-500">to {{ $campaign->end_date?->format('d M Y') ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">{{ $campaign->banners_count ?? 0 }}</td>
                        <td class="px-6 py-4">
                            @if($campaign->is_active && (!$campaign->end_date || $campaign->end_date->isFuture()))
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}"
                                    class="text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST"
                                    onsubmit="return confirm('Delete this campaign?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">No campaigns found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.admin>