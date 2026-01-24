<div class="min-h-screen bg-slate-900 text-white">
    <div class="flex">
        <!-- Sidebar -->
        @include('pages.admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <div class="bg-slate-800 border-b border-slate-700 px-8 py-6 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold">Dashboard Overview</h1>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <input type="text" placeholder="Search for bookings, rooms..." 
                               class="bg-slate-700 text-white rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="bg-amber-400 w-10 h-10 rounded-full flex items-center justify-center text-slate-900 font-bold">
                            AR
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-8">
                <!-- Top Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Total Revenue -->
                    <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-slate-400 text-sm mb-1">Total Revenue</p>
                                <h3 class="text-3xl font-bold">${{ number_format($totalRevenue / 1000, 0) }}K</h3>
                            </div>
                            <span class="text-green-400 text-sm font-semibold">+{{ number_format($revenueGrowth, 1) }}%</span>
                        </div>
                        <p class="text-slate-500 text-xs">{{ number_format($revenueGrowth, 1) }}% vs last month</p>
                    </div>

                    <!-- Total Bookings -->
                    <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-slate-400 text-sm mb-1">Total Bookings</p>
                                <h3 class="text-3xl font-bold">{{ $totalBookings }}</h3>
                            </div>
                            <span class="text-green-400 text-sm font-semibold">+4.2%</span>
                        </div>
                        <p class="text-slate-500 text-xs">+4.2% vs yesterday</p>
                    </div>

                    <!-- New Customers -->
                    <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-slate-400 text-sm mb-1">New Customers</p>
                                <h3 class="text-3xl font-bold">{{ $newCustomers }}</h3>
                            </div>
                            <span class="text-green-400 text-sm font-semibold">+15.4%</span>
                        </div>
                        <p class="text-slate-500 text-xs">+15.4% from yesterday</p>
                    </div>

                    <!-- Occupancy Rate -->
                    <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-slate-400 text-sm mb-1">Occupancy Rate</p>
                                <h3 class="text-3xl font-bold">{{ number_format($occupancyRate, 0) }}%</h3>
                            </div>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $occupancyRate }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Revenue Chart -->
                    <div class="lg:col-span-2 bg-slate-800 rounded-lg p-6 border border-slate-700">
                        <h2 class="text-lg font-semibold mb-6">Revenue Overview</h2>
                        <p class="text-slate-400 text-xs mb-4">Monthly revenue trend for the current year</p>
                        <div class="h-64 flex items-end justify-around gap-2">
                            @foreach($monthlyRevenue as $data)
                                <div class="flex flex-col items-center gap-2 flex-1">
                                    <div class="w-full bg-gradient-to-t from-blue-500 to-blue-600 rounded-t" 
                                         style="height: {{ ($data['revenue'] / 72000) * 100 }}%"></div>
                                    <span class="text-slate-400 text-xs">{{ $data['month'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Room Availability -->
                    <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
                        <h2 class="text-lg font-semibold mb-6">Room Availability</h2>
                        <div class="space-y-4">
                            @foreach($roomAvailability as $room)
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br {{ $room['color'] }} flex items-center justify-center">
                                        {{ $room['icon'] }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">{{ $room['name'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $room['available'] }}/{{ $room['total'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg transition">
                            View Detailed Reports
                        </button>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="bg-slate-800 rounded-lg p-6 border border-slate-700">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold">Recent Bookings</h2>
                        <a href="#" class="text-blue-400 hover:text-blue-300 text-sm font-medium">View All Bookings</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-700">
                                    <th class="text-left py-3 px-4 text-slate-400 text-sm font-medium">BOOKING ID</th>
                                    <th class="text-left py-3 px-4 text-slate-400 text-sm font-medium">GUEST NAME</th>
                                    <th class="text-left py-3 px-4 text-slate-400 text-sm font-medium">ROOM TYPE</th>
                                    <th class="text-left py-3 px-4 text-slate-400 text-sm font-medium">CHECK-IN / CHECK-OUT</th>
                                    <th class="text-left py-3 px-4 text-slate-400 text-sm font-medium">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                    <tr class="border-b border-slate-700 hover:bg-slate-700/50 transition">
                                        <td class="py-4 px-4 text-sm">{{ $booking['id'] }}</td>
                                        <td class="py-4 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-purple-600 flex items-center justify-center text-xs font-bold">
                                                    {{ $booking['initials'] }}
                                                </div>
                                                <span class="text-sm">{{ $booking['guest'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-sm">{{ $booking['room'] }}</td>
                                        <td class="py-4 px-4 text-sm text-slate-400">{{ $booking['dates'] }}</td>
                                        <td class="py-4 px-4">
                                            @if($booking['status'] === 'confirmed')
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-900/30 text-green-400">Confirmed</span>
                                            @elseif($booking['status'] === 'pending')
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-yellow-900/30 text-yellow-400">Pending</span>
                                            @elseif($booking['status'] === 'cancelled')
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-red-900/30 text-red-400">Cancelled</span>
                                            @else
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-900/30 text-blue-400">{{ ucfirst($booking['status']) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
