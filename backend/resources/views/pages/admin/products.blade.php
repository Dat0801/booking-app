<div class="min-h-screen bg-gray-100">
    <div class="max-w-6xl mx-auto py-8 px-4">
        <div class="flex gap-6">
            @include('pages.admin.partials.sidebar')

            <div class="flex-1 space-y-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-semibold">Products</h1>
                    <button
                        type="button"
                        wire:click="createProduct"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        New product
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input
                                type="text"
                                wire:model.debounce.500ms="search"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="Name, slug or description"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select
                                wire:model="type"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="">All</option>
                                <option value="product">Product</option>
                                <option value="service">Service</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select
                                wire:model="categoryId"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="0">All</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select
                                wire:model="isActive"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">ID</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Name</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Type</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Category</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Price</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Status</th>
                                <th class="px-4 py-2 text-left font-medium text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr>
                                    <td class="px-4 py-2 text-gray-700">#{{ $product->id }}</td>
                                    <td class="px-4 py-2 text-gray-700">{{ $product->name }}</td>
                                    <td class="px-4 py-2 text-gray-700 capitalize">{{ $product->type }}</td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ optional($product->category)->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-700">
                                        {{ number_format($product->price, 0, '.', ',') }} đ
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($product->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 space-x-2">
                                        <button
                                            type="button"
                                            wire:click="editProduct({{ $product->id }})"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 rounded hover:bg-indigo-100"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            type="button"
                                            wire:click="deleteProduct({{ $product->id }})"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-700 bg-red-50 rounded hover:bg-red-100"
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        No products found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>

                @if($showFormModal)
                    <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-40">
                        <div class="w-full max-w-xl bg-white rounded-lg shadow-lg mx-4">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                                <h2 class="text-lg font-semibold">
                                    {{ $productId ? 'Edit product' : 'New product' }}
                                </h2>
                                <button
                                    type="button"
                                    wire:click="$set('showFormModal', false)"
                                    class="text-gray-400 hover:text-gray-600"
                                >
                                    &times;
                                </button>
                            </div>

                            <form wire:submit.prevent="saveProduct" class="px-4 py-4 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                        <input
                                            type="text"
                                            wire:model="formName"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                        @error('formName')
                                            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                                        <input
                                            type="text"
                                            wire:model="formSlug"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                            placeholder="Leave blank to auto-generate"
                                        >
                                        @error('formSlug')
                                            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                        <select
                                            wire:model="formType"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                            <option value="product">Product</option>
                                            <option value="service">Service</option>
                                        </select>
                                        @error('formType')
                                            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                        <select
                                            wire:model="formCategoryId"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                            <option value="0">None</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('formCategoryId')
                                            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <select
                                            wire:model="formIsActive"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        @error('formIsActive')
                                            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            wire:model="formPrice"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                        @error('formPrice')
                                            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                                        <input
                                            type="number"
                                            wire:model="formDurationMinutes"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                        @error('formDurationMinutes')
                                            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock quantity</label>
                                        <input
                                            type="number"
                                            wire:model="formStockQuantity"
                                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        >
                                        @error('formStockQuantity')
                                            <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                                    <input
                                        type="text"
                                        wire:model="formImageUrl"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    >
                                    @error('formImageUrl')
                                        <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea
                                        wire:model="formDescription"
                                        rows="3"
                                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    ></textarea>
                                    @error('formDescription')
                                        <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-end space-x-3 pt-2">
                                    <button
                                        type="button"
                                        wire:click="$set('showFormModal', false)"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700"
                                    >
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
