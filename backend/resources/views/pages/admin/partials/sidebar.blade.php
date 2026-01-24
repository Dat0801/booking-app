<aside class="w-64 bg-slate-800 border-r border-slate-700 flex flex-col h-screen sticky top-0">
    <!-- Logo Section -->
    <div class="px-6 py-6 border-b border-slate-700">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white">BookingAdmin</h1>
                <p class="text-xs text-slate-400">Property Management</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4V3"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.bookings') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.bookings') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <span>Bookings</span>
        </a>

        <a href="{{ route('admin.rooms') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.rooms') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
            <span>Rooms</span>
        </a>

        <a href="{{ route('admin.users') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.users') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2z"></path>
            </svg>
            <span>Users</span>
        </a>

        <a href="{{ route('admin.reports') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.reports') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-700' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span>Reports</span>
        </a>
    </nav>

    <!-- Footer -->
    <div class="px-4 py-4 border-t border-slate-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 flex-1">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-sm font-bold text-slate-900">
                    AR
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">Alex Rivera</p>
                    <p class="text-xs text-slate-400 truncate">Administrator</p>
                </div>
            </div>
            <button class="p-1 hover:bg-slate-700 rounded-lg transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
            </button>
        </div>
    </div>
</aside>

