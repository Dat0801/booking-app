<div class="min-h-screen flex bg-gray-900">
    <!-- Sidebar -->
    <div class="w-64 flex-shrink-0">
        @include('pages.admin.partials.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen">
        <!-- Header -->
        <div class="bg-gray-800 border-b border-gray-700 px-8 py-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-white">Booking Management</h1>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
                <span>+</span> New Reservation
            </button>
        </div>

        <!-- Content Area -->
        <div class="flex-1 px-8 py-6 space-y-6">
            <!-- Filter Tabs -->
            <div class="flex items-center justify-between">
                <div class="flex gap-4 border-b border-gray-700">
                    <button 
                        wire:click="$set('status', '')"
                        class="px-4 py-3 text-sm font-medium transition-colors {{ $status === '' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-300' }}">
                        All
                    </button>
                    <button 
                        wire:click="$set('status', 'pending')"
                        class="px-4 py-3 text-sm font-medium transition-colors {{ $status === 'pending' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-300' }}">
                        Pending
                    </button>
                    <button 
                        wire:click="$set('status', 'confirmed')"
                        class="px-4 py-3 text-sm font-medium transition-colors {{ $status === 'confirmed' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-300' }}">
                        Confirmed
                    </button>
                    <button 
                        wire:click="$set('status', 'completed')"
                        class="px-4 py-3 text-sm font-medium transition-colors {{ $status === 'completed' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-300' }}">
                        Completed
                    </button>
                    <button 
                        wire:click="$set('status', 'cancelled')"
                        class="px-4 py-3 text-sm font-medium transition-colors {{ $status === 'cancelled' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-gray-300' }}">
                        Cancelled
                    </button>
                </div>
                <div class="flex gap-3">
                    <div class="relative">
                        <input
                            type="text"
                            wire:model.debounce.500ms="search"
                            class="bg-gray-800 border border-gray-700 text-white px-4 py-2 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Search bookings..."
                        >
                    </div>
                    <button class="bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-300 px-4 py-2 rounded-lg text-sm font-medium">
                        More Filters
                    </button>
                    <button class="bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-300 px-4 py-2 rounded-lg text-sm font-medium">
                        Export
                    </button>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-900 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">GUEST</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">PROPERTY/ROOM</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">STAY DATES</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">AMOUNT</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">STATUS</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-300 uppercase tracking-wider">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-blue-400">#BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1) . (substr(explode(' ', $booking->user->name ?? 'User')[1] ?? '', 0, 1) ?: '')) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-white">{{ $booking->user->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-400">{{ $booking->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-300">{{ $booking->product->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->product->location ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    @if($booking->scheduled_date)
                                        <div>{{ $booking->scheduled_date->format('M d') }}</div>
                                        <div class="text-xs text-gray-500">- {{ $booking->scheduled_date->addDays(1)->format('M d, Y') }}</div>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-white">
                                    {{ isset($booking->total_amount) ? '$' . number_format($booking->total_amount, 2) : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $paymentStatus = $booking->payment_status ?? 'unpaid';
                                        $statusBgColor = match($paymentStatus) {
                                            'paid' => 'bg-green-900 text-green-200',
                                            'pending' => 'bg-yellow-900 text-yellow-200',
                                            'partially_paid' => 'bg-blue-900 text-blue-200',
                                            default => 'bg-red-900 text-red-200',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium {{ $statusBgColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm flex gap-3">
                                    <button
                                        wire:click="editBooking({{ $booking->id }})"
                                        class="text-gray-400 hover:text-blue-400 transition-colors"
                                        title="View"
                                    >
                                        👁️
                                    </button>
                                    <button
                                        wire:click="editBooking({{ $booking->id }})"
                                        class="text-gray-400 hover:text-yellow-400 transition-colors"
                                        title="Edit"
                                    >
                                        ✏️
                                    </button>
                                    <button
                                        wire:click="deleteBooking({{ $booking->id }})"
                                        class="text-gray-400 hover:text-red-400 transition-colors"
                                        title="Delete"
                                    >
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm">
                                    No bookings found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-700 bg-gray-900 flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} entries
                    </div>
                    <div class="flex gap-1">
                        {{ $bookings->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-3 gap-6">
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Monthly Revenue</p>
                            <p class="text-3xl font-bold text-white">${{ number_format($monthlyRevenue, 0) }}</p>
                        </div>
                        <div class="bg-gray-700 rounded-lg p-3">
                            <span class="text-2xl">📊</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Total Bookings</p>
                            <p class="text-3xl font-bold text-white">{{ number_format($totalBookings) }}</p>
                        </div>
                        <div class="bg-gray-700 rounded-lg p-3">
                            <span class="text-2xl">📅</span>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Pending Approvals</p>
                            <p class="text-3xl font-bold text-white">{{ $pendingCount }}</p>
                        </div>
                        <div class="bg-gray-700 rounded-lg p-3">
                            <span class="text-2xl">⏳</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                @if($editingBookingId)
                    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-60">
                        <div class="w-full max-w-md bg-gray-800 rounded-lg shadow-xl mx-4 border border-gray-700">
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
                                <h2 class="text-lg font-semibold text-white">
                                    Update Booking #{{ str_pad($editingBookingId, 4, '0', STR_PAD_LEFT) }}
                                </h2>
                                <button
                                    type="button"
                                    wire:click="$set('editingBookingId', null)"
                                    class="text-gray-400 hover:text-gray-300 text-2xl leading-none"
                                >
                                    &times;
                                </button>
                            </div>

                            <form wire:submit.prevent="saveBooking" class="px-6 py-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                                    <select
                                        wire:model="formStatus"
                                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="pending">Pending</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    @error('formStatus')
                                        <div class="mt-1 text-xs text-red-400">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Payment Status</label>
                                    <select
                                        wire:model="formPaymentStatus"
                                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    >
                                        <option value="paid">Paid</option>
                                        <option value="pending">Pending</option>
                                        <option value="unpaid">Unpaid</option>
                                        <option value="partially_paid">Partially Paid</option>
                                    </select>
                                    @error('formPaymentStatus')
                                        <div class="mt-1 text-xs text-red-400">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-end gap-3 pt-4">
                                    <button
                                        type="button"
                                        wire:click="$set('editingBookingId', null)"
                                        class="px-4 py-2 text-sm font-medium text-gray-300 bg-gray-700 border border-gray-600 rounded-md hover:bg-gray-600 transition-colors"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors"
                                    >
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
