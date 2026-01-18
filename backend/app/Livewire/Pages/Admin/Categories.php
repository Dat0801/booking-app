<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Categories · Booking App Admin')]
class Categories extends Component
{
    use WithPagination;

    public string $search = '';

    public string $isActive = '';

    public int $perPage = 15;

    public ?int $categoryId = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public string $formIsActive = '1';

    public bool $showFormModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'isActive' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingIsActive(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function createCategory(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function editCategory(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->categoryId = $category->id;
        $this->name = (string) $category->name;
        $this->slug = (string) ($category->slug ?? '');
        $this->description = (string) ($category->description ?? '');
        $this->formIsActive = $category->is_active ? '1' : '0';
        $this->showFormModal = true;
    }

    public function saveCategory(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'formIsActive' => ['required', 'in:0,1'],
        ]);

        $payload = [
            'name' => $data['name'],
            'slug' => $data['slug'] !== '' ? $data['slug'] : null,
            'description' => $data['description'] !== '' ? $data['description'] : null,
            'is_active' => $data['formIsActive'] === '1',
        ];

        if (! $payload['slug']) {
            $payload['slug'] = Str::slug($payload['name']);
        }

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update($payload);
        } else {
            Category::create($payload);
            $this->resetPage();
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function deleteCategory(int $id): void
    {
        $category = Category::findOrFail($id);
        $category->delete();
        $this->resetPage();
    }

    protected function resetForm(): void
    {
        $this->categoryId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->formIsActive = '1';
    }

    public function render()
    {
        $query = Category::query();

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        if ($this->isActive !== '') {
            $isActive = $this->isActive === '1';
            $query->where('is_active', $isActive);
        }

        $categories = $query
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('pages.admin.categories', [
            'categories' => $categories,
        ]);
    }
}
