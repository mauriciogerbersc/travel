<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@travel.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Usuário',
                'email' => 'user@travel.com',
                'password' => Hash::make('user123')
            ]
        ];

        foreach ($users as $user) {
            User::factory()->create(
                $user
            );
        }
    }
}
