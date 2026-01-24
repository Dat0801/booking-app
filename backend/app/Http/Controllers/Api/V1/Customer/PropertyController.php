<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Catalog\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(private ProductService $products)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['per_page']);
        $filters['type'] = 'property';

        $properties = $this->products->list($filters);

        return response()->json($properties);
    }

    public function show(int $id): JsonResponse
    {
        $product = $this->products->get($id);

        if (! $product || $product->type !== 'property') {
            abort(404);
        }

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
}
