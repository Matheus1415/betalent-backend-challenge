<?php

namespace App\Services;

use App\Models\Gateway;

class GatewayService
{
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $paginator = Gateway::query()
            ->when(isset($filters['name']), fn($q) => $q->where('name', 'like', "%{$filters['name']}%"))
            ->orderBy('priority', 'asc')
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
        return Gateway::create($data);
    }

    public function update(Gateway $gateway, array $data)
    {
        $gateway->fill($data);
        return $gateway;
    }
}