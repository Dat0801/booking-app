<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Catalog\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private ProductService $products)
    {
    }

    public function categories(): JsonResponse
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['type', 'category_id', 'search', 'per_page']);

        $products = $this->products->list($filters);

        return response()->json($products);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->products->get($id);

        if (! $product) {
            abort(404);
        }

        return response()->json($product);
    }
}

