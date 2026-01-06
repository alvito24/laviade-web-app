<x-layouts.admin title="Users Management">
    <div class="mb-6 flex justify-between">
        <form class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search users..."
                class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
            <button type="submit" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($users as $user)
                    <tr>
                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:underline">View</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-600 hover:underline">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->withQueryString()->links() }}
    </div>
</x-layouts.admin>