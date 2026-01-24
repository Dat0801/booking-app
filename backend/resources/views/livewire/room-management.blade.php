<div class="min-h-screen bg-slate-900 text-white">
    <div class="flex">
        <!-- Sidebar -->
        @include('pages.admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <div class="bg-slate-800 border-b border-slate-700 px-8 py-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Room Management</h1>
                </div>
                <div class="flex items-center gap-4">
                    <button 
                        wire:click="openForm()"
                        class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold flex items-center gap-2 transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Room
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-6 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-blue-200 mb-2">Total Rooms</p>
                                <p class="text-4xl font-bold">{{ $totalRooms }}</p>
                                <p class="text-blue-200 text-sm mt-2">📈 +10%</p>
                            </div>
                            <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-green-600 to-green-700 p-6 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-green-200 mb-2">Available Rooms</p>
                                <p class="text-4xl font-bold">{{ $availableRooms }}</p>
                                <p class="text-green-200 text-sm mt-2">✅ +2%</p>
                            </div>
                            <svg class="w-12 h-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-red-600 to-red-700 p-6 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-red-200 mb-2">Booked Rooms</p>
                                <p class="text-4xl font-bold">{{ $bookedRooms }}</p>
                                <p class="text-red-200 text-sm mt-2">❌ -1%</p>
                            </div>
                            <svg class="w-12 h-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Search & Filter Bar -->
                <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="relative">
                            <svg class="absolute left-3 top-3 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input 
                                type="text" 
                                wire:model.live="searchTerm"
                                placeholder="Search by room name or location..."
                                class="w-full pl-10 pr-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500"
                            >
                        </div>

                        <select 
                            wire:model.live="filterType"
                            class="px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white focus:outline-none focus:border-blue-500"
                        >
                            <option value="all">All Room Types</option>
                            <option value="room">Standard Room</option>
                            <option value="suite">Suite</option>
                            <option value="villa">Villa</option>
                            <option value="apartment">Apartment</option>
                        </select>

                        <select 
                            wire:change="updateSort('name')"
                            class="px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white focus:outline-none focus:border-blue-500"
                        >
                            <option value="">Sort by Name</option>
                            <option value="price_asc">Price: Low to High</option>
                            <option value="price_desc">Price: High to Low</option>
                        </select>

                        <button 
                            wire:click="$refresh"
                            class="px-4 py-2 bg-slate-700 hover:bg-slate-600 border border-slate-600 rounded text-white transition flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>

                <!-- Success Message -->
                @if (session()->has('message'))
                    <div class="bg-green-900 border border-green-700 text-green-200 px-6 py-4 rounded-lg flex justify-between items-center">
                        <span>{{ session('message') }}</span>
                        <button wire:click="$refresh" class="text-green-400 hover:text-green-200">✕</button>
                    </div>
                @endif

                <!-- Room Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($rooms as $room)
                        <div class="bg-slate-800 rounded-lg overflow-hidden border border-slate-700 hover:border-blue-500 transition group">
                            <!-- Room Image -->
                            <div class="relative h-48 bg-slate-700 overflow-hidden">
                                @if ($room->image_url && is_string($room->image_url))
                                    <img src="{{ $room->image_url }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-700">
                                        <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <div class="absolute top-3 left-3">
                                    @if ($room->is_active)
                                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold">✓ Active</span>
                                    @else
                                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">✕ Inactive</span>
                                    @endif
                                </div>

                                <!-- Stock Badge -->
                                <div class="absolute bottom-3 right-3">
                                    @if ($room->stock_quantity > 0)
                                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold">{{ $room->stock_quantity }} Available</span>
                                    @else
                                        <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">Fully Booked</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Room Info -->
                            <div class="p-4">
                                <h3 class="text-xl font-bold mb-2">{{ $room->name }}</h3>
                                
                                <!-- Location -->
                                <div class="flex items-center gap-2 text-slate-400 text-sm mb-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $room->location }}
                                </div>

                                <!-- Description -->
                                <p class="text-slate-400 text-sm mb-3 line-clamp-2">{{ $room->description }}</p>

                                <!-- Rating -->
                                @if ($room->rating)
                                    <div class="flex items-center gap-1 mb-3">
                                        @for ($i = 0; $i < floor($room->rating); $i++)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <span class="text-sm text-slate-400">{{ number_format($room->rating, 1) }} ({{ $room->review_count ?? 0 }} reviews)</span>
                                    </div>
                                @endif

                                <!-- Amenities -->
                                @if ($room->amenities && is_array($room->amenities) && count($room->amenities) > 0)
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @foreach (array_slice($room->amenities, 0, 3) as $amenity)
                                            @if (is_string($amenity))
                                                <span class="bg-slate-700 text-slate-300 text-xs px-2 py-1 rounded">{{ $amenity }}</span>
                                            @endif
                                        @endforeach
                                        @if (count($room->amenities) > 3)
                                            <span class="bg-slate-700 text-slate-300 text-xs px-2 py-1 rounded">+{{ count($room->amenities) - 3 }}</span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Price -->
                                <div class="border-t border-slate-700 pt-3 mb-4">
                                    <p class="text-2xl font-bold text-blue-400">${{ number_format($room->price, 2) }}<span class="text-sm text-slate-400">/night</span></p>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button 
                                        wire:click="openForm({{ $room->id }})"
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded text-sm font-semibold transition flex items-center justify-center gap-1"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </button>
                                    <button 
                                        wire:click="toggleActive({{ $room->id }})"
                                        class="flex-1 {{ $room->is_active ? 'bg-slate-700 hover:bg-slate-600' : 'bg-green-600 hover:bg-green-700' }} px-3 py-2 rounded text-sm font-semibold transition"
                                    >
                                        {{ $room->is_active ? '✕ Disable' : '✓ Enable' }}
                                    </button>
                                    <button 
                                        wire:click="deleteRoom({{ $room->id }})"
                                        wire:confirm="Are you sure you want to delete this room?"
                                        class="flex-1 bg-red-600 hover:bg-red-700 px-3 py-2 rounded text-sm font-semibold transition"
                                    >
                                        🗑️ Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="w-16 h-16 text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-slate-400 text-lg">No rooms found</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($rooms->hasPages())
                    <div class="flex justify-center">
                        {{ $rooms->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    @if ($showForm)
        <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4" wire:click="closeForm">
            <div class="bg-slate-800 rounded-lg max-w-2xl w-full border border-slate-700" @click.stop>
                <!-- Modal Header -->
                <div class="border-b border-slate-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-2xl font-bold">
                        {{ $editingRoomId ? 'Edit Room' : 'Create New Room' }}
                    </h2>
                    <button wire:click="closeForm" class="text-slate-400 hover:text-white text-2xl">✕</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 max-h-96 overflow-y-auto">
                    <form wire:submit="saveRoom" class="space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Room Name *</label>
                            <input 
                                type="text"
                                wire:model="name"
                                placeholder="Enter room name..."
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500"
                            >
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Category *</label>
                            <select 
                                wire:model="category_id"
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white focus:outline-none focus:border-blue-500"
                            >
                                <option value="">Select category...</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Location *</label>
                            <input 
                                type="text"
                                wire:model="location"
                                placeholder="Enter room location..."
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500"
                            >
                            @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Description *</label>
                            <textarea 
                                wire:model="description"
                                placeholder="Enter room description..."
                                rows="3"
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500"
                            ></textarea>
                            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Price & Stock -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2">Price ($/night) *</label>
                                <input 
                                    type="number"
                                    step="0.01"
                                    wire:model="price"
                                    placeholder="0.00"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500"
                                >
                                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2">Available Rooms *</label>
                                <input 
                                    type="number"
                                    wire:model="stock_quantity"
                                    placeholder="0"
                                    class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500"
                                >
                                @error('stock_quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Rating (0-5)</label>
                            <input 
                                type="number"
                                step="0.1"
                                min="0"
                                max="5"
                                wire:model="rating"
                                placeholder="4.5"
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white placeholder-slate-400 focus:outline-none focus:border-blue-500"
                            >
                            @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Amenities -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Tiện Nghi</label>
                            <div class="flex gap-2 mb-3">
                                <input 
                                    type="text"
                                    id="amenityInput"
                                    placeholder="Nhập tiện nghi (VD: WiFi, TV, AC)..."
                                    class="flex-1 px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                                    @keydown.enter="$wire.addAmenity($el.value); $el.value = ''"
                                >
                                <button 
                                    type="button"
                                    @click="$wire.addAmenity(document.getElementById('amenityInput').value); document.getElementById('amenityInput').value = ''"
                                    class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded font-semibold transition"
                                >
                                    Thêm
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($amenities as $index => $amenity)
                                    @if (is_string($amenity))
                                        <div class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm flex items-center gap-2">
                                            {{ $amenity }}
                                            <button 
                                            type="button"
                                            wire:click="removeAmenity({{ $index }})"
                                            class="hover:text-red-300"
                                        >
                                            ✕
                                        </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Image -->
                        <div>
                            <label class="block text-sm font-semibold mb-2">Room Image</label>
                            <input 
                                type="file"
                                wire:model="imageFile"
                                accept="image/*"
                                class="w-full px-4 py-2 bg-slate-700 border border-slate-600 rounded text-slate-400 focus:outline-none focus:border-blue-500"
                            >
                            @error('imageFile') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @if ($imageFile)
                                <p class="text-green-400 text-sm mt-1">✓ New image selected</p>
                            @elseif ($image_url && is_string($image_url))
                                <p class="text-slate-400 text-sm mt-1">Current image: {{ basename($image_url) }}</p>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="border-t border-slate-700 px-6 py-4 flex gap-3 justify-end">
                    <button 
                        wire:click="closeForm"
                        class="px-6 py-2 bg-slate-700 hover:bg-slate-600 rounded font-semibold transition"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="saveRoom"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded font-semibold transition"
                    >
                        {{ $editingRoomId ? 'Update' : 'Create Room' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush
