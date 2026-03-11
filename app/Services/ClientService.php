<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $paginator = Client::query()
            ->when($filters['name'] ?? null, function ($query, $name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($filters['email'] ?? null, function ($query, $email) {
                $query->where('email', 'like', "%{$email}%");
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
        return Client::create([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }

    public function update(Client $user, array $data)
    {
        $user->fill($data);
        $user->save();

        return $user;
    }
}