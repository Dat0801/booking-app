<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;

class RoomManagement extends Component
{
    use WithPagination;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:1000')]
    public $description = '';

    #[Validate('required|numeric|min:0')]
    public $price = '';

    #[Validate('required|integer|min:1')]
    public $stock_quantity = '';

    #[Validate('required|exists:categories,id')]
    public $category_id = '';

    #[Validate('required|string')]
    public $location = '';

    #[Validate('nullable|numeric|min:0|max:5')]
    public $rating = '';

    #[Validate('nullable|array')]
    public $amenities = [];

    #[Validate('nullable|string')]
    public $image_url = '';

    public $searchTerm = '';
    public $sortBy = 'created_at';
    public $sortDir = 'desc';
    public $filterType = 'all';
    public $editingRoomId = null;
    public $showForm = false;
    public $imageFile = null;
    public $categories = [];

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->categories = Category::all();
    }

    public function render()
    {
        $query = Product::query();

        if ($this->searchTerm) {
            $query->where('name', 'like', "%{$this->searchTerm}%")
                  ->orWhere('location', 'like', "%{$this->searchTerm}%");
        }

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $rooms = $query->orderBy($this->sortBy, $this->sortDir)
                      ->paginate(12);

        $totalRooms = Product::count();
        $availableRooms = Product::where('stock_quantity', '>', 0)->count();
        $bookedRooms = Product::where('stock_quantity', '<=', 0)->count();

        return view('livewire.room-management', [
            'rooms' => $rooms,
            'categories' => $this->categories,
            'totalRooms' => $totalRooms,
            'availableRooms' => $availableRooms,
            'bookedRooms' => $bookedRooms,
        ]);
    }

    public function openForm($roomId = null)
    {
        if ($roomId) {
            $this->editingRoomId = $roomId;
            $room = Product::findOrFail($roomId);
            $this->name = $room->name;
            $this->description = $room->description;
            $this->price = $room->price;
            $this->stock_quantity = $room->stock_quantity;
            $this->category_id = $room->category_id;
            $this->location = $room->location;
            $this->rating = $room->rating;
            $this->amenities = is_array($room->amenities) ? $room->amenities : [];
            $this->image_url = is_string($room->image_url) ? $room->image_url : '';
        }
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->editingRoomId = null;
        $this->resetFormFields();
    }

    public function resetFormFields()
    {
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->stock_quantity = '';
        $this->category_id = '';
        $this->location = '';
        $this->rating = '';
        $this->amenities = [];
        $this->image_url = '';
        $this->imageFile = null;
    }

    public function saveRoom()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => \Illuminate\Support\Str::slug($this->name),
            'description' => $this->description,
            'price' => $this->price,
            'stock_quantity' => $this->stock_quantity,
            'category_id' => $this->category_id,
            'location' => $this->location,
            'rating' => $this->rating ?: 0,
            'amenities' => $this->amenities,
            'type' => 'room',
            'is_active' => true,
        ];

        if ($this->imageFile) {
            $path = $this->imageFile->store('rooms', 'public');
            $data['image_url'] = Storage::url($path);
        } elseif ($this->editingRoomId && !$this->image_url) {
            $data['image_url'] = null;
        } elseif ($this->image_url) {
            $data['image_url'] = $this->image_url;
        }

        if ($this->editingRoomId) {
            $room = Product::findOrFail($this->editingRoomId);
            $room->update($data);
            session()->flash('message', 'Phòng đã được cập nhật thành công!');
        } else {
            Product::create($data);
            session()->flash('message', 'Phòng mới đã được tạo thành công!');
        }

        $this->closeForm();
        $this->resetPage();
    }

    public function deleteRoom($roomId)
    {
        Product::findOrFail($roomId)->delete();
        session()->flash('message', 'Phòng đã được xóa thành công!');
        $this->resetPage();
    }

    public function toggleActive($roomId)
    {
        $room = Product::findOrFail($roomId);
        $room->update(['is_active' => !$room->is_active]);
        session()->flash('message', 'Trạng thái phòng đã được cập nhật!');
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

    public function addAmenity($amenity)
    {
        if ($amenity && !in_array($amenity, $this->amenities)) {
            $this->amenities[] = $amenity;
        }
    }

    public function removeAmenity($index)
    {
        unset($this->amenities[$index]);
        $this->amenities = array_values($this->amenities);
    }
}
