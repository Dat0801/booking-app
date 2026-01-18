<div class="min-h-screen bg-gray-100">
    <div class="max-w-6xl mx-auto py-10 px-4">
        <div class="flex gap-6">
            @include('pages.admin.partials.sidebar')

            <div class="flex-1 space-y-8">
                <h1 class="text-2xl font-semibold">Admin Dashboard</h1>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm text-gray-500 mb-2">Customers</div>
                        <div class="text-3xl font-bold">{{ $usersCount }}</div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm text-gray-500 mb-2">Orders</div>
                        <div class="text-3xl font-bold">{{ $ordersCount }}</div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm text-gray-500 mb-2">Bookings</div>
                        <div class="text-3xl font-bold">{{ $bookingsCount }}</div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm text-gray-500 mb-2">Products</div>
                        <div class="text-3xl font-bold">{{ $productsCount }}</div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm text-gray-500 mb-2">Orders today</div>
                        <div class="text-3xl font-bold">{{ $todayOrdersCount }}</div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm text-gray-500 mb-2">Bookings today</div>
                        <div class="text-3xl font-bold">{{ $todayBookingsCount }}</div>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="text-sm text-gray-500 mb-2">Total revenue</div>
                        <div class="text-3xl font-bold">
                            {{ number_format($totalRevenue, 0, '.', ',') }} đ
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
