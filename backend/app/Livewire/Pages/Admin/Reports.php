<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Booking;
use App\Models\Product;
use Carbon\Carbon;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Reports & Analytics · Booking App Admin')]
class Reports extends Component
{
    public string $dateFrom;
    public string $dateTo;
    public float $totalRevenue = 0.0;
    public float $avgDailyRate = 0.0;
    public float $occupancyRate = 0.0;
    public int $totalBookings = 0;
    public array $revenueTrend = [];
    public array $bookingSource = [];
    public array $roomTypePerformance = [];
    public array $monthlyPerformance = [];

    public function mount(): void
    {
        $this->dateFrom = now()->subMonths(3)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadReportsData();
    }

    public function updated($property): void
    {
        if (in_array($property, ['dateFrom', 'dateTo'])) {
            $this->loadReportsData();
        }
    }

    private function loadReportsData(): void
    {
        $startDate = Carbon::parse($this->dateFrom)->startOfDay();
        $endDate = Carbon::parse($this->dateTo)->endOfDay();

        // Total Revenue
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->get();

        $this->totalRevenue = (float) $bookings->sum('total_amount');

        // Average Daily Rate
        $daysCount = $startDate->diffInDays($endDate) ?: 1;
        $this->avgDailyRate = $this->totalRevenue / $daysCount;

        // Total Bookings
        $this->totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();

        // Occupancy Rate
        $totalRooms = Product::count();
        $bookedRooms = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();
        $this->occupancyRate = $totalRooms > 0 ? ($bookedRooms / $totalRooms) * 100 : 0;

        // Revenue Trend (Monthly)
        $this->revenueTrend = [
            ['month' => 'JAN', 'revenue' => 45000],
            ['month' => 'FEB', 'revenue' => 52000],
            ['month' => 'MAR', 'revenue' => 48000],
            ['month' => 'APR', 'revenue' => 61000],
            ['month' => 'MAY', 'revenue' => 55000],
            ['month' => 'JUN', 'revenue' => 67000],
            ['month' => 'JUL', 'revenue' => 72000],
            ['month' => 'AUG', 'revenue' => 58000],
            ['month' => 'SEP', 'revenue' => 65000],
            ['month' => 'OCT', 'revenue' => 78000],
            ['month' => 'NOV', 'revenue' => 85000],
            ['month' => 'DEC', 'revenue' => 72000],
        ];

        // Booking Source
        $this->bookingSource = [
            ['source' => 'Direct Booking', 'count' => 289, 'percentage' => 45],
            ['source' => 'OTA (Expedia, Booking)', 'count' => 225, 'percentage' => 35],
            ['source' => 'Mobile App', 'count' => 128, 'percentage' => 20],
        ];

        // Room Type Performance
        $this->roomTypePerformance = [
            ['type' => 'Deluxe Suite', 'revenue' => 52400, 'percentage' => 40],
            ['type' => 'Standard Double', 'revenue' => 38200, 'percentage' => 31],
            ['type' => 'Single Superior', 'revenue' => 22100, 'percentage' => 18],
            ['type' => 'Penthouse', 'revenue' => 11800, 'percentage' => 9],
        ];

        // Monthly Performance Table
        $this->monthlyPerformance = [
            [
                'month' => 'October 2023',
                'bookings' => 312,
                'revenue' => '$58,240',
                'occupancy' => '84.5%',
                'avg_rate' => '$186.67',
                'cancellations' => 12,
            ],
            [
                'month' => 'September 2023',
                'bookings' => 289,
                'revenue' => '$52,100',
                'occupancy' => '79.2%',
                'avg_rate' => '$180.90',
                'cancellations' => 18,
            ],
            [
                'month' => 'August 2023',
                'bookings' => 345,
                'revenue' => '$68,900',
                'occupancy' => '92.7%',
                'avg_rate' => '$199.71',
                'cancellations' => 9,
            ],
            [
                'month' => 'July 2023',
                'bookings' => 330,
                'revenue' => '$64,500',
                'occupancy' => '89.6%',
                'avg_rate' => '$195.45',
                'cancellations' => 15,
            ],
        ];
    }

    public function applyDateRange(): void
    {
        $this->loadReportsData();
    }

    public function render()
    {
        return view('livewire.pages.admin.reports');
    }
}
