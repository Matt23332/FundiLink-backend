<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;
use Illuminate\Support\Facades\File;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        // Sample image URLs (you can use placeholder images)
        $sampleImages = [
            'https://picsum.photos/id/20/800/600',  // Workspace
            'https://picsum.photos/id/26/800/600',  // Nature
            'https://picsum.photos/id/30/800/600',  // Coffee
            'https://picsum.photos/id/42/800/600',  // Piano
            'https://picsum.photos/id/48/800/600',  // Architecture
            'https://picsum.photos/id/60/800/600',  // Model
        ];
        
        $services = [
            ['user_id' => 2, 'name' => 'Carpentry Services', 'description' => 'Skilled carpentry services for custom furniture and home improvement.', 'price' => 2000, 'category_id' => 2, 'location' => 'City C', 'contact_info' => '555-123-4567'],
            ['user_id' => 2, 'name' => 'Tutoring Services', 'description' => 'Experienced tutors for academic support and skill development.', 'price' => 500, 'category_id' => 3, 'location' => 'City D', 'contact_info' => '444-987-6543'],
            ['user_id' => 2, 'name' => 'Event Planning Services', 'description' => 'Creative event planning services for weddings, parties, and corporate events.', 'price' => 3000, 'category_id' => 4, 'location' => 'City E', 'contact_info' => '333-555-7777'],
        ];
        
        foreach ($services as $index => $serviceData) {
            $serviceData['image_path'] = $sampleImages[$index % count($sampleImages)];
            Service::create($serviceData);
        }
    }
}
