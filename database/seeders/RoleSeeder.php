<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'description' => 'Full access to all resources.'],
            ['name' => 'Service Provider', 'description' => 'Can manage job postings and view service requests.'],
            ['name' => 'Customer', 'description' => 'Can search for services and apply for them.'],
        ];

        foreach ($roles as $role) {
            \App\Models\Role::create($role);
        }
    }
}
