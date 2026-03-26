<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index() {
        $services = Service::all();
        return response()->json(['message' => 'Services retrieved successfully', 'services' => $services], 200);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $service = Service::create($validated);
        return response()->json(['message' => 'Service created successfully', 'service' => $service], 201);
    }

    public function show($id) {
        $service = Service::findOrFail($id);
        return response()->json(['message' => 'Service retrieved successfully', 'service' => $service], 200);
    }

    public function update(Request $request, $id) {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'image_path' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $service->update($validated);
        return response()->json(['message' => 'Service updated successfully', 'service' => $service], 200);
    }

    public function destroy($id) {
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully'], 200);
    }
}
