<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Orders · Booking App Admin')]
class Orders extends Component
{
    use WithPagination;

    public string $search = '';

    public string $status = '';

    public string $paymentStatus = '';

    public int $userId = 0;

    public string $fromDate = '';

    public string $toDate = '';

    public bool $withDeleted = false;

    public int $perPage = 15;

    public ?int $editingOrderId = null;

    public string $formStatus = '';

    public string $formPaymentStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'paymentStatus' => ['except' => ''],
        'userId' => ['except' => 0],
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

    public function editOrder(int $id): void
    {
        $order = Order::findOrFail($id);

        $this->editingOrderId = $order->id;
        $this->formStatus = (string) $order->status;
        $this->formPaymentStatus = (string) ($order->payment_status ?? '');
    }

    public function saveOrder(): void
    {
        if (! $this->editingOrderId) {
            return;
        }

        $id = $this->editingOrderId;

        $data = $this->validate([
            'formStatus' => ['required', 'string', 'max:50'],
            'formPaymentStatus' => ['nullable', 'string', 'max:50'],
        ]);

        $order = Order::findOrFail($id);

        $order->status = $data['formStatus'];
        $order->payment_status = $data['formPaymentStatus'] !== '' ? $data['formPaymentStatus'] : $order->payment_status;
        $order->save();

        $this->editingOrderId = null;
    }

    public function render()
    {
        $query = Order::query()->with(['user', 'payments']);

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
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

        if ($this->fromDate !== '') {
            $query->whereDate('placed_at', '>=', $this->fromDate);
        }

        if ($this->toDate !== '') {
            $query->whereDate('placed_at', '<=', $this->toDate);
        }

        if ($this->withDeleted) {
            $query->withTrashed();
        }

        $orders = $query
            ->orderByDesc('placed_at')
            ->orderByDesc('id')
            ->paginate($this->perPage);

        return view('pages.admin.orders', [
            'orders' => $orders,
        ]);
    }
}
