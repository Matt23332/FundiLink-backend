<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Home Services', 'description' => 'Services related to home maintenance and improvement.'],
            ['name' => 'Plumbing', 'description' => 'Services for plumbing repair, maintenance, and installation.'],
            ['name' => 'Carpentry', 'description' => 'Services for woodworking, furniture making, and repairs.'],
            ['name' => 'Education & Tutoring', 'description' => 'Services for academic support and skill development.'],
            ['name' => 'Event Planning', 'description' => 'Services for organizing and managing events.'],
        ];

        foreach ($categories as $category) {
            \App\Models\Categories::create($category);
        }
    }
}
