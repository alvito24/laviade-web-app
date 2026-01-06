<x-layouts.app title="My Addresses">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            @include('user.profile.partials.sidebar')

            <div class="flex-1">
                <div class="bg-surface rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">My Addresses</h2>
                        <button onclick="openAddModal()" class="btn-primary text-sm py-2 px-4 rounded">
                            + Add Address
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-6">{{ session('success') }}</div>
                    @endif

                    @if($addresses->count() > 0)
                        <div class="space-y-4">
                            @foreach($addresses as $address)
                                <div
                                    class="bg-white rounded-lg p-4 border border-custom {{ $address->is_primary ? 'ring-2 ring-black' : '' }}">
                                    <div class="flex justify-between mb-2">
                                        <div class="font-semibold flex items-center gap-2">
                                            {{ $address->display_label }}
                                            @if($address->is_primary)
                                                <span class="text-xs bg-black text-white px-2 py-0.5 rounded">Primary</span>
                                            @endif
                                        </div>
                                        <div class="flex gap-2">
                                            @if(!$address->is_primary)
                                                <form action="{{ route('profile.addresses.primary', $address) }}" method="POST"
                                                    class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-sm text-secondary hover:text-black">Set
                                                        Primary</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-sm">
                                        <p class="font-medium">{{ $address->recipient_name }}</p>
                                        <p class="text-secondary">{{ $address->phone }}</p>
                                        <p class="text-secondary mt-1">{{ $address->full_address }}</p>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-custom flex gap-3">
                                        <button onclick="editAddress({{ json_encode($address) }})"
                                            class="text-sm text-secondary hover:text-black">Edit</button>
                                        <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Hapus alamat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm text-red-600 hover:text-red-800">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            <h3 class="font-medium mb-2">No addresses saved</h3>
                            <p class="text-secondary mb-4">Add an address for faster checkout</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="address-modal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-lg w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <h3 id="modal-title" class="text-lg font-bold mb-4">Add New Address</h3>
                <form id="address-form" method="POST" action="{{ route('profile.addresses.store') }}">
                    @csrf
                    <div id="method-field"></div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Label</label>
                            <input type="text" name="label" id="inp-label" placeholder="Rumah, Kantor, dll"
                                class="w-full px-3 py-2 border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Recipient Name *</label>
                            <input type="text" name="recipient_name" id="inp-recipient_name" required
                                class="w-full px-3 py-2 border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Phone *</label>
                            <input type="tel" name="phone" id="inp-phone" required
                                class="w-full px-3 py-2 border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Province *</label>
                                <input type="text" name="province" id="inp-province" required
                                    class="w-full px-3 py-2 border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">City *</label>
                                <input type="text" name="city" id="inp-city" required
                                    class="w-full px-3 py-2 border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">District *</label>
                                <input type="text" name="district" id="inp-district" required
                                    class="w-full px-3 py-2 border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Postal Code *</label>
                                <input type="text" name="postal_code" id="inp-postal_code" required
                                    class="w-full px-3 py-2 border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Address Detail *</label>
                            <textarea name="address_detail" id="inp-address_detail" rows="2" required
                                class="w-full px-3 py-2 border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300"></textarea>
                        </div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_primary" id="inp-is_primary" value="1">
                            <span class="text-sm">Set as primary address</span>
                        </label>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeModal()"
                            class="btn-secondary flex-1 py-2 rounded-lg">Cancel</button>
                        <button type="submit" class="btn-primary flex-1 py-2 rounded-lg">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function openAddModal() {
                document.getElementById('modal-title').textContent = 'Add New Address';
                document.getElementById('address-form').action = '{{ route("profile.addresses.store") }}';
                document.getElementById('method-field').innerHTML = '';
                document.getElementById('address-form').reset();
                document.getElementById('address-modal').classList.remove('hidden');
            }

            function editAddress(address) {
                document.getElementById('modal-title').textContent = 'Edit Address';
                document.getElementById('address-form').action = `/profile/addresses/${address.id}`;
                document.getElementById('method-field').innerHTML = '@method("PUT")';

                ['label', 'recipient_name', 'phone', 'province', 'city', 'district', 'postal_code', 'address_detail'].forEach(field => {
                    document.getElementById('inp-' + field).value = address[field] || '';
                });
                document.getElementById('inp-is_primary').checked = address.is_primary;

                document.getElementById('address-modal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('address-modal').classList.add('hidden');
            }
        </script>
    @endpush
</x-layouts.app>