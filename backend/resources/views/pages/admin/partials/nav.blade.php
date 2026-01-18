<div class="bg-white shadow">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.dashboard') }}" class="text-lg font-semibold text-gray-800">
                Admin
            </a>
            <div class="hidden md:flex items-center space-x-3 text-sm">
                <a href="{{ route('admin.users') }}" class="px-2 py-1 rounded hover:bg-gray-100">Users</a>
                <a href="{{ route('admin.categories') }}" class="px-2 py-1 rounded hover:bg-gray-100">Categories</a>
                <a href="{{ route('admin.products') }}" class="px-2 py-1 rounded hover:bg-gray-100">Products</a>
                <a href="{{ route('admin.orders') }}" class="px-2 py-1 rounded hover:bg-gray-100">Orders</a>
                <a href="{{ route('admin.bookings') }}" class="px-2 py-1 rounded hover:bg-gray-100">Bookings</a>
            </div>
        </div>
    </div>
</div>

