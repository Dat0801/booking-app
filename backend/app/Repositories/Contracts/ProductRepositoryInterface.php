<?php
namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function paginate(array $filters = []): LengthAwarePaginator;
    public function find(int $id): ?Product;
}