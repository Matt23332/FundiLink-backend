<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'Admin', 'phone' => '1234567890', 'address' => '123 Admin St, City, Country'],
            ['name' => 'Service Provider', 'email' => 'serviceprovider@example.com', 'password' => bcrypt('password'), 'role' => 'Service Provider', 'phone' => '0987654321', 'address' => '456 Service St, City, Country'],
            ['name' => 'Customer User', 'email' => 'customer@example.com', 'password' => bcrypt('password'), 'role' => 'Customer', 'phone' => '1122334455', 'address' => '789 Customer St, City, Country'],
        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }

    }
}
