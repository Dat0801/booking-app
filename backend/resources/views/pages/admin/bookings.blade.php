<div class="min-h-screen bg-gray-100">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex gap-6">
            @include('pages.admin.partials.sidebar')

            <div class="flex-1 space-y-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold">Bookings</h1>
                </div>

                <div class="bg-white rounded-lg shadow p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input
                                type="text"
                                wire:model.debounce.500ms="search"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Booking number or customer"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <input
                                type="text"
                                wire:model="status"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Status"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment status</label>
                            <input
                                type="text"
                                wire:model="paymentStatus"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Payment status"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">User ID</label>
                            <input
                                type="number"
                                wire:model="userId"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="User ID"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Product ID</label>
                            <input
                                type="number"
                                wire:model="productId"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Product ID"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">From date</label>
                            <input
                                type="date"
                                wire:model="fromDate"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To date</label>
                            <input
                                type="date"
                                wire:model="toDate"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="flex items-end space-x-3">
                            <label class="inline-flex items-center text-sm text-gray-700">
                                <input
                                    type="checkbox"
                                    wire:model="withDeleted"
                                    class="mr-2 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >
                                Include deleted
                            </label>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Booking</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Customer</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Product</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Status</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Payment</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Scheduled at</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr>
                                    <td class="px-4 py-2 text-gray-700">
                                        <div class="font-medium">#{{ $booking->booking_number }}</div>
                                        <div class="text-xs text-gray-500">ID: {{ $booking->id }}</div>
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        <div class="font-medium">{{ $booking->user->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->user->email ?? '' }}</div>
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $booking->product->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-800">
                                            {{ $booking->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-800">
                                            {{ $booking->payment_status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ $booking->scheduled_date ? $booking->scheduled_date->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-2">
                                        <button
                                            type="button"
                                            wire:click="editBooking({{ $booking->id }})"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 rounded hover:bg-indigo-100"
                                        >
                                            Update
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                        No bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} bookings
                        </div>
                        <div>
                            {{ $bookings->links() }}
                        </div>
                    </div>
                </div>

                @if($editingBookingId)
                    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-40">
                        <div class="w-full max-w-md bg-white rounded-lg shadow-lg mx-4">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                                <h2 class="text-lg font-semibold">
                                    Update booking #{{ $editingBookingId }}
                                </h2>
                                <button
                                    type="button"
                                    wire:click="$set('editingBookingId', null)"
                                    class="text-gray-400 hover:text-gray-600"
                                >
                                    &times;
                                </button>
                            </div>

                            <form wire:submit.prevent="saveBooking" class="px-4 py-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <input
                                        type="text"
                                        wire:model="formStatus"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                    @error('formStatus')
                                        <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment status</label>
                                    <input
                                        type="text"
                                        wire:model="formPaymentStatus"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                    @error('formPaymentStatus')
                                        <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-end space-x-3 pt-2">
                                    <button
                                        type="button"
                                        wire:click="$set('editingBookingId', null)"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
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
