<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceRequests = [
            ['service_id' => 1, 'user_id' => 3, 'status' => 'Pending', 'price' => 1000, 'request_date' => '2024-07-01', 'description' => 'Need plumbing services for kitchen sink.', 'address' => '789 Customer St, City, Country'],
            ['service_id' => 2, 'user_id' => 3, 'status' => 'Completed', 'price' => 1500, 'request_date' => '2024-06-15', 'description' => 'Require electrical work for living room lighting.', 'address' => '123 Customer St, City, Country'],
        ];

        foreach ($serviceRequests as $request) {
            \App\Models\ServiceRequest::create($request);
        }
    }
}
