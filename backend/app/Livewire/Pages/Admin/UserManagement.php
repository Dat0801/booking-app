<?php

namespace App\Livewire\Pages\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class UserManagement extends Component
{
    use WithPagination;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|email|max:255|unique:users,email')]
    public $email = '';

    #[Validate('required|string|min:8')]
    public $password = '';

    #[Validate('nullable|regex:/^[0-9\-\+\s\(\)]{6,}$/')]
    public $phone = '';

    #[Validate('boolean')]
    public $is_active = true;

    public $searchTerm = '';
    public $sortBy = 'created_at';
    public $sortDir = 'desc';
    public $filterStatus = 'all';
    public $editingUserId = null;
    public $showForm = false;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $query = User::query();

        if ($this->searchTerm) {
            $query->where('name', 'like', "%{$this->searchTerm}%")
                  ->orWhere('email', 'like', "%{$this->searchTerm}%");
        }

        if ($this->filterStatus !== 'all') {
            $query->where('is_active', $this->filterStatus === 'active');
        }

        $users = $query->orderBy($this->sortBy, $this->sortDir)
                       ->paginate(10);

        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $bannedUsers = User::where('is_active', false)->count();

        return view('livewire.pages.admin.user-management', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'bannedUsers' => $bannedUsers,
        ]);
    }

    public function openForm($userId = null)
    {
        if ($userId) {
            $this->editingUserId = $userId;
            $user = User::findOrFail($userId);
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->is_active = $user->is_active;
            $this->password = '';
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingUserId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->phone = '';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function saveUser()
    {
        if ($this->editingUserId) {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
                'password' => 'nullable|string|min:8',
                'phone' => 'nullable|regex:/^[0-9\-\+\s\(\)]{6,}$/',
                'is_active' => 'boolean',
            ]);
        } else {
            $this->validate();
        }

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_active' => $this->is_active,
        ];

        if ($this->password && !$this->editingUserId) {
            $data['password'] = bcrypt($this->password);
        } elseif ($this->password && $this->editingUserId) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->editingUserId) {
            $user = User::findOrFail($this->editingUserId);
            $user->update($data);
            session()->flash('message', 'Người dùng đã được cập nhật thành công!');
        } else {
            User::create($data);
            session()->flash('message', 'Người dùng mới đã được tạo thành công!');
        }

        $this->closeForm();
        $this->resetPage();
    }

    public function deleteUser($userId)
    {
        User::findOrFail($userId)->delete();
        session()->flash('message', 'Người dùng đã được xóa thành công!');
        $this->resetPage();
    }

    public function toggleActive($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('message', 'Trạng thái người dùng đã được cập nhật!');
    }

    public function updateSort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function exportCSV()
    {
        $users = User::orderBy($this->sortBy, $this->sortDir)->get();
        
        $csv = "Full Name,Email Address,Phone,Join Date,Total Bookings,Status\n";
        
        foreach ($users as $user) {
            $csv .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",%d,\"%s\"\n",
                $user->name,
                $user->email,
                $user->phone ?? '',
                $user->created_at->format('M d, Y'),
                $user->bookings()->count(),
                $user->is_active ? 'Active' : 'Banned'
            );
        }

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, 'users-' . date('Y-m-d-H-i-s') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
