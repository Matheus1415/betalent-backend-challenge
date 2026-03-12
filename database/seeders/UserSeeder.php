<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Sistema Admin',
                'email' => 'admin@betalent.tech',
                'role' => 'ADMIN',
            ],
            [
                'name' => 'Gerente de Vendas',
                'email' => 'manager@betalent.tech',
                'role' => 'MANAGER',
            ],
            [
                'name' => 'Financeiro Senior',
                'email' => 'finance@betalent.tech',
                'role' => 'FINANCE',
            ],
            [
                'name' => 'Cliente Comum',
                'email' => 'user@betalent.tech',
                'role' => 'USER',
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('  '),
                    'role' => $userData['role'],
                ]
            );
        }
    }
}