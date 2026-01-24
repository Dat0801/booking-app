<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Booking;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Bookings · Booking App Admin')]
class Bookings extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $paymentStatus = '';

    public int $userId = 0;

    public int $productId = 0;

    public string $fromDate = '';

    public string $toDate = '';

    public bool $withDeleted = false;

    public int $perPage = 10;

    public ?int $editingBookingId = null;

    public string $formStatus = '';

    public string $formPaymentStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
        'userId' => ['except' => 0],
        'productId' => ['except' => 0],
        'fromDate' => ['except' => ''],
        'toDate' => ['except' => ''],
        'withDeleted' => ['except' => false],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingPaymentStatus(): void
    {
        $this->resetPage();
    }

    public function updatingUserId(): void
    {
        $this->resetPage();
    }

    public function updatingProductId(): void
    {
        $this->resetPage();
    }

    public function updatingFromDate(): void
    {
        $this->resetPage();
    }

    public function updatingToDate(): void
    {
        $this->resetPage();
    }

    public function updatingWithDeleted(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function editBooking(int $id): void
    {
        $booking = Booking::findOrFail($id);

        $this->editingBookingId = $booking->id;
        $this->formStatus = (string) $booking->status;
        $this->formPaymentStatus = (string) ($booking->payment_status ?? '');
    }

    public function saveBooking(): void
    {
        if (! $this->editingBookingId) {
            return;
        }

        $id = $this->editingBookingId;

        $data = $this->validate([
            'formStatus' => ['required', 'string', 'max:50'],
            'formPaymentStatus' => ['nullable', 'string', 'max:50'],
        ]);

        $booking = Booking::findOrFail($id);

        $booking->status = $data['formStatus'];
        $booking->payment_status = $data['formPaymentStatus'] !== '' ? $data['formPaymentStatus'] : $booking->payment_status;
        $booking->save();

        $this->editingBookingId = null;
    }

    public function deleteBooking(int $id): void
    {
        Booking::findOrFail($id)->delete();
    }

    public function render()
    {
        $query = Booking::query()->with(['user', 'product', 'payments']);

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->paymentStatus !== '') {
            $query->where('payment_status', $this->paymentStatus);
        }

        if ($this->userId > 0) {
            $query->where('user_id', $this->userId);
        }

        if ($this->productId > 0) {
            $query->where('product_id', $this->productId);
        }

        if ($this->fromDate !== '') {
            $query->whereDate('scheduled_date', '>=', $this->fromDate);
        }

        if ($this->toDate !== '') {
            $query->whereDate('scheduled_date', '<=', $this->toDate);
        }

        if ($this->withDeleted) {
            $query->withTrashed();
        }

        $bookings = $query
            ->orderByDesc('scheduled_date')
            ->orderByDesc('id')
            ->paginate($this->perPage);

        // Calculate statistics
        $allBookings = Booking::query();
        $monthlyRevenue = Booking::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');
        
        $totalBookings = $allBookings->count();
        $pendingCount = Booking::where('status', 'pending')->count();

        return view('pages.admin.bookings', [
            'bookings' => $bookings,
            'monthlyRevenue' => $monthlyRevenue,
            'totalBookings' => $totalBookings,
            'pendingCount' => $pendingCount,
        ]);
    }
}
