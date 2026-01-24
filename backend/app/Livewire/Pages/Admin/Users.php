<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Users · Booking App Admin')]
class Users extends Component
{
    use WithPagination;

    public string $search = '';

    public string $role = '';

    public string $isActive = '';

    public bool $withDeleted = false;

    public int $perPage = 15;

    public ?int $userId = null;

    public string $formName = '';

    public string $formEmail = '';

    public string $formPhone = '';

    public string $formIsActive = '1';

    public array $formRoles = [];

    public bool $showFormModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => ''],
        'isActive' => ['except' => ''],
        'withDeleted' => ['except' => false],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRole(): void
    {
        $this->resetPage();
    }

    public function updatingIsActive(): void
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

    public function editUser(?int $id): void
    {
        if ($id === null) {
            $this->resetForm();
            $this->showFormModal = true;
            return;
        }

        $user = User::withTrashed()->with('roles')->findOrFail($id);

        $this->userId = $user->id;
        $this->formName = (string) $user->name;
        $this->formEmail = (string) $user->email;
        $this->formPhone = (string) ($user->phone ?? '');
        $this->formIsActive = $user->is_active ? '1' : '0';
        $this->formRoles = $user->roles->pluck('name')->all();
        $this->showFormModal = true;
    }

    public function saveUser(): void
    {
        // Validate data - adjust uniqueness for creation vs update
        if ($this->userId) {
            $data = $this->validate([
                'formName' => ['required', 'string', 'max:255'],
                'formEmail' => ['required', 'email', 'max:255', 'unique:users,email,' . $this->userId],
                'formPhone' => ['nullable', 'string', 'max:50'],
                'formIsActive' => ['required', 'in:0,1'],
                'formRoles' => ['array'],
                'formRoles.*' => ['string', 'distinct'],
            ]);

            $user = User::withTrashed()->findOrFail($this->userId);
            $user->name = $data['formName'];
            $user->email = $data['formEmail'];
            $user->phone = $data['formPhone'] !== '' ? $data['formPhone'] : null;
            $user->is_active = $data['formIsActive'] === '1';
            $user->save();
        } else {
            $data = $this->validate([
                'formName' => ['required', 'string', 'max:255'],
                'formEmail' => ['required', 'email', 'max:255', 'unique:users,email'],
                'formPhone' => ['nullable', 'string', 'max:50'],
                'formIsActive' => ['required', 'in:0,1'],
                'formRoles' => ['array'],
                'formRoles.*' => ['string', 'distinct'],
            ]);

            $user = User::create([
                'name' => $data['formName'],
                'email' => $data['formEmail'],
                'phone' => $data['formPhone'] !== '' ? $data['formPhone'] : null,
                'is_active' => $data['formIsActive'] === '1',
                'password' => bcrypt('password123'), // Default password
            ]);
        }

        if (! empty($data['formRoles'])) {
            $roleIds = Role::query()
                ->whereIn('name', $data['formRoles'])
                ->pluck('id')
                ->all();

            $user->roles()->sync($roleIds);
        } else {
            $user->roles()->sync([]);
        }

        $this->showFormModal = false;
        $this->resetForm();
        $this->resetPage();
    }

    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
        $this->resetPage();
    }

    public function restoreUser(int $id): void
    {
        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
        }

        $this->resetPage();
    }

    protected function resetForm(): void
    {
        $this->userId = null;
        $this->formName = '';
        $this->formEmail = '';
        $this->formPhone = '';
        $this->formIsActive = '1';
        $this->formRoles = [];
    }

    public function render()
    {
        $query = User::query()->with('roles');

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        if ($this->isActive !== '') {
            $isActive = $this->isActive === '1';
            $query->where('is_active', $isActive);
        }

        if ($this->role !== '') {
            $role = $this->role;
            $query->whereHas('roles', function ($rq) use ($role) {
                $rq->where('name', $role);
            });
        }

        if ($this->withDeleted) {
            $query->withTrashed();
        }

        $users = $query
            ->withCount(['orders', 'bookings'])
            ->orderByDesc('id')
            ->paginate($this->perPage);

        $roles = Role::query()
            ->orderBy('name')
            ->get();

        return view('pages.admin.users', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
