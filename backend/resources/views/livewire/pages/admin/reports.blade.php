<div class="min-h-screen bg-slate-900 text-white">
    <div class="flex">
        <!-- Sidebar -->
        @include('pages.admin.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <div class="bg-slate-800 border-b border-slate-700 px-8 py-6">
                <div class="flex justify-between items-center">
                    <h1 class="text-3xl font-bold">Reports & Analytics</h1>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8 space-y-8">
                <!-- Date Range Picker -->
                <div class="bg-slate-800 p-6 rounded-lg border border-slate-700 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold mb-2">Track your property's financial performance and occupancy trends.</h2>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex gap-2 items-center">
                            <input 
                                type="date" 
                                wire:model="dateFrom"
                                class="px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white focus:outline-none focus:border-blue-500"
                            >
                            <span class="text-slate-400">to</span>
                            <input 
                                type="date" 
                                wire:model="dateTo"
                                class="px-4 py-2 bg-slate-700 border border-slate-600 rounded text-white focus:outline-none focus:border-blue-500"
                            >
                        </div>
                        <button 
                            wire:click="applyDateRange()"
                            class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg font-semibold transition"
                        >
                            Apply Range
                        </button>
                    </div>
                </div>

                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Total Revenue -->
                    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-sm mb-2">Total Revenue</p>
                                <p class="text-3xl font-bold">${{ number_format($totalRevenue, 2) }}</p>
                                <p class="text-green-400 text-sm mt-2">📈 +12.5%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Avg Daily Rate -->
                    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-sm mb-2">Avg Daily Rate</p>
                                <p class="text-3xl font-bold">${{ number_format($avgDailyRate, 2) }}</p>
                                <p class="text-green-400 text-sm mt-2">📈 +3.2%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Occupancy % -->
                    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-sm mb-2">Occupancy %</p>
                                <p class="text-3xl font-bold">{{ number_format($occupancyRate, 1) }}%</p>
                                <p class="text-green-400 text-sm mt-2">📈 +5.1%</p>
                            </div>
                        </div>
                    </div>

                    <!-- Total Bookings -->
                    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-slate-400 text-sm mb-2">Total Bookings</p>
                                <p class="text-3xl font-bold">{{ $totalBookings }}</p>
                                <p class="text-red-400 text-sm mt-2">📉 -1.2%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Trend Chart -->
                <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Revenue Trend (Monthly)</h2>
                        <div class="flex gap-2">
                            <button class="px-4 py-1 rounded bg-blue-600 text-sm font-semibold">Monthly</button>
                            <button class="px-4 py-1 rounded bg-slate-700 text-sm font-semibold text-slate-300">Yearly</button>
                        </div>
                    </div>
                    
                    <!-- Simple Bar Chart -->
                    <div class="h-64 flex items-end justify-center gap-1 px-6 py-4">
                        @php
                            $maxRevenue = max(array_column($revenueTrend, 'revenue'));
                        @endphp
                        @foreach($revenueTrend as $data)
                            <div class="flex flex-col items-center flex-1">
                                <div 
                                    class="w-full bg-gradient-to-t from-blue-500 to-blue-400 rounded-t"
                                    style="height: {{ ($data['revenue'] / $maxRevenue) * 100 }}%"
                                ></div>
                                <p class="text-xs text-slate-400 mt-2">{{ $data['month'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Booking Source -->
                    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                        <h2 class="text-xl font-bold mb-6">Booking Source</h2>
                        
                        <div class="flex items-center justify-center">
                            <div class="relative w-48 h-48">
                                <!-- Pie Chart Placeholder - Using SVG for visual -->
                                <svg class="w-full h-full" viewBox="0 0 100 100">
                                    <!-- Pie slices for visual representation -->
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="#3b82f6" stroke-width="15" stroke-dasharray="70.6 100" transform="rotate(-90 50 50)"/>
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="#8b5cf6" stroke-width="15" stroke-dasharray="55 100" transform="rotate(-90 50 50) translate(0)" style="stroke-dashoffset: -70.6;"/>
                                    <circle cx="50" cy="50" r="40" fill="none" stroke="#06b6d4" stroke-width="15" stroke-dasharray="31.4 100" transform="rotate(-90 50 50) translate(0)" style="stroke-dashoffset: -125.6;"/>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="text-center">
                                        <p class="text-3xl font-bold">642</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3 mt-6">
                            @foreach($bookingSource as $source)
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-3 h-3 rounded-full" 
                                             style="background-color: {{ $loop->first ? '#3b82f6' : ($loop->iteration === 2 ? '#8b5cf6' : '#06b6d4') }}"></div>
                                        <span class="text-sm">{{ $source['source'] }}</span>
                                    </div>
                                    <span class="font-semibold">{{ $source['count'] }} ({{ $source['percentage'] }}%)</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Room Type Performance -->
                    <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                        <h2 class="text-xl font-bold mb-6">Room Type Performance</h2>
                        
                        <div class="space-y-4">
                            @foreach($roomTypePerformance as $room)
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium">{{ $room['type'] }}</span>
                                        <span class="text-sm font-semibold">${{ number_format($room['revenue'], 0) }} ({{ $room['percentage'] }}%)</span>
                                    </div>
                                    <div class="w-full bg-slate-700 rounded-full h-2">
                                        <div 
                                            class="bg-blue-500 h-2 rounded-full"
                                            style="width: {{ $room['percentage'] }}%"
                                        ></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Monthly Performance Table -->
                <div class="bg-slate-800 p-6 rounded-lg border border-slate-700">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Monthly Performance Table</h2>
                        <a href="#" class="text-blue-400 hover:text-blue-300 text-sm font-medium">View All Records →</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="border-b border-slate-700">
                                <tr class="text-slate-400 text-left">
                                    <th class="px-4 py-3 font-medium">Month</th>
                                    <th class="px-4 py-3 font-medium">Bookings</th>
                                    <th class="px-4 py-3 font-medium">Gross Revenue</th>
                                    <th class="px-4 py-3 font-medium">Occupancy</th>
                                    <th class="px-4 py-3 font-medium">Avg Rate</th>
                                    <th class="px-4 py-3 font-medium">Cancellations</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700">
                                @foreach($monthlyPerformance as $month)
                                    <tr class="hover:bg-slate-700 transition">
                                        <td class="px-4 py-4 font-medium">{{ $month['month'] }}</td>
                                        <td class="px-4 py-4">{{ $month['bookings'] }}</td>
                                        <td class="px-4 py-4 font-semibold">{{ $month['revenue'] }}</td>
                                        <td class="px-4 py-4">{{ $month['occupancy'] }}</td>
                                        <td class="px-4 py-4">{{ $month['avg_rate'] }}</td>
                                        <td class="px-4 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-500 bg-opacity-20 text-red-400">
                                                {{ $month['cancellations'] }}
                                            </span>
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
