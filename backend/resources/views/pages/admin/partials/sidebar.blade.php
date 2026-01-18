<div class="w-56 shrink-0">
    <div class="bg-white rounded-lg shadow p-4 space-y-4">
        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
            Navigation
        </div>
        <nav class="space-y-1 text-sm">
            <a
                href="{{ route('admin.dashboard') }}"
                class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}"
            >
                <span>Dashboard</span>
            </a>
            <a
                href="{{ route('admin.users') }}"
                class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('admin.users') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}"
            >
                <span>Users</span>
            </a>
            <a
                href="{{ route('admin.categories') }}"
                class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('admin.categories') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}"
            >
                <span>Categories</span>
            </a>
            <a
                href="{{ route('admin.products') }}"
                class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('admin.products') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}"
            >
                <span>Products</span>
            </a>
            <a
                href="{{ route('admin.orders') }}"
                class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('admin.orders') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}"
            >
                <span>Orders</span>
            </a>
            <a
                href="{{ route('admin.bookings') }}"
                class="flex items-center px-3 py-2 rounded-md {{ request()->routeIs('admin.bookings') ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}"
            >
                <span>Bookings</span>
            </a>
        </nav>
    </div>
</div>

