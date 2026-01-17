<?php
namespace App\Services\Catalog;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(private ProductRepositoryInterface $products)
    {
    }

    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->products->paginate($filters);
    }

    public function get(int $id)
    {
        return $this->products->find($id);
    }
}