<x-layouts.app title="My Profile">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            @include('user.profile.partials.sidebar')

            <!-- Content -->
            <div class="flex-1">
                <div class="bg-surface rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-6">Edit Profile</h2>

                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar -->
                        <div class="mb-6 flex items-center gap-6">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                                class="w-24 h-24 rounded-full object-cover">
                            <div>
                                <label class="btn-secondary inline-block cursor-pointer rounded">
                                    Change Photo
                                    <input type="file" name="avatar" accept="image/*" class="hidden">
                                </label>
                                <p class="text-xs text-secondary mt-2">JPG, PNG max 2MB</p>
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email (readonly) -->
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full px-4 py-3 bg-gray-100 border border-custom rounded-lg text-secondary cursor-not-allowed">
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Phone Number</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-3 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                        </div>

                        <!-- Gender -->
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Gender</label>
                            <select name="gender"
                                class="w-full px-4 py-3 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>
                                    Female</option>
                                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                        </div>

                        <!-- Birth Date -->
                        <div class="mb-6">
                            <label class="block font-medium mb-2">Birth Date</label>
                            <input type="date" name="birth_date"
                                value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                        </div>

                        <button type="submit" class="btn-primary rounded-lg">
                            Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>