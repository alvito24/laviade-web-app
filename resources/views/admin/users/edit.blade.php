<x-layouts.admin title="Edit User: {{ $user->name }}">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6 border-b">
            <h2 class="font-semibold">Edit User Details</h2>
        </div>
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-black">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="rounded text-black focus:ring-black">
                        <span class="text-sm font-medium">Active Account</span>
                    </label>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.users.show', $user) }}" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Update User</button>
            </div>
        </form>
        
        <div class="p-6 bg-red-50 border-t border-red-100">
            <h3 class="text-red-800 font-semibold mb-2">Danger Zone</h3>
            <p class="text-red-600 text-sm mb-4">Deleting a user is irreversible. Be careful.</p>
            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Delete User</button>
            </form>
        </div>
    </div>
</x-layouts.admin>
