<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products · Booking App Admin')]
class Products extends Component
{
    use WithPagination;

    public string $search = '';

    public string $type = '';

    public string $isActive = '';

    public int $categoryId = 0;

    public int $perPage = 15;

    public ?int $productId = null;

    public string $formName = '';

    public string $formSlug = '';

    public string $formType = 'product';

    public int $formCategoryId = 0;

    public ?float $formPrice = null;

    public ?int $formDurationMinutes = null;

    public ?int $formStockQuantity = null;

    public string $formIsActive = '1';

    public string $formImageUrl = '';

    public string $formDescription = '';

    public bool $showFormModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'isActive' => ['except' => ''],
        'categoryId' => ['except' => 0],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingIsActive(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function createProduct(): void
    {
        $this->resetForm();
        $this->showFormModal = true;
    }

    public function editProduct(int $id): void
    {
        $product = Product::findOrFail($id);

        $this->productId = $product->id;
        $this->formName = (string) $product->name;
        $this->formSlug = (string) ($product->slug ?? '');
        $this->formType = (string) $product->type;
        $this->formCategoryId = (int) ($product->category_id ?? 0);
        $this->formPrice = $product->price;
        $this->formDurationMinutes = $product->duration_minutes;
        $this->formStockQuantity = $product->stock_quantity;
        $this->formIsActive = $product->is_active ? '1' : '0';
        $this->formImageUrl = (string) ($product->image_url ?? '');
        $this->formDescription = (string) ($product->description ?? '');
        $this->showFormModal = true;
    }

    public function saveProduct(): void
    {
        $data = $this->validate([
            'formCategoryId' => ['nullable', 'integer', 'exists:categories,id'],
            'formType' => ['required', 'string', 'in:product,service'],
            'formName' => ['required', 'string', 'max:255'],
            'formSlug' => ['nullable', 'string', 'max:255'],
            'formDescription' => ['nullable', 'string'],
            'formPrice' => ['required', 'numeric', 'min:0'],
            'formDurationMinutes' => ['nullable', 'integer', 'min:0'],
            'formStockQuantity' => ['nullable', 'integer', 'min:0'],
            'formIsActive' => ['required', 'in:0,1'],
            'formImageUrl' => ['nullable', 'string', 'max:2048'],
        ]);

        $payload = [
            'category_id' => $data['formCategoryId'] ?: null,
            'type' => $data['formType'],
            'name' => $data['formName'],
            'slug' => $data['formSlug'] !== '' ? $data['formSlug'] : null,
            'description' => $data['formDescription'] !== '' ? $data['formDescription'] : null,
            'price' => $data['formPrice'],
            'duration_minutes' => $data['formDurationMinutes'] ?? null,
            'stock_quantity' => $data['formStockQuantity'] ?? null,
            'is_active' => $data['formIsActive'] === '1',
            'image_url' => $data['formImageUrl'] !== '' ? $data['formImageUrl'] : null,
        ];

        if (! $payload['slug']) {
            $payload['slug'] = Str::slug($payload['name']);
        }

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($payload);
        } else {
            Product::create($payload);
            $this->resetPage();
        }

        $this->showFormModal = false;
        $this->resetForm();
    }

    public function deleteProduct(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->delete();
        $this->resetPage();
    }

    protected function resetForm(): void
    {
        $this->productId = null;
        $this->formName = '';
        $this->formSlug = '';
        $this->formType = 'product';
        $this->formCategoryId = 0;
        $this->formPrice = null;
        $this->formDurationMinutes = null;
        $this->formStockQuantity = null;
        $this->formIsActive = '1';
        $this->formImageUrl = '';
        $this->formDescription = '';
    }

    public function render()
    {
        $query = Product::query();

        if ($this->search !== '') {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($this->type !== '') {
            $query->where('type', $this->type);
        }

        if ($this->categoryId > 0) {
            $query->where('category_id', $this->categoryId);
        }

        if ($this->isActive !== '') {
            $isActive = $this->isActive === '1';
            $query->where('is_active', $isActive);
        }

        $products = $query
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('pages.admin.products', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
}
