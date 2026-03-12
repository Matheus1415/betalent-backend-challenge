<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $paginator = Product::query()
             ->when($filters['name'] ?? null, function ($query, $name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($filters['amount'] ?? null, function ($query, $amount) {
                $query->where('amount', 'like', "%{$amount}%");
            })
            ->orderBy($filters['sort'] ?? 'created_at', $filters['order'] ?? 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return [
            'data' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ]
        ];
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->fill($data);
        $product->save();
        
        return $product;
    }

}