<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;


class ServiceController extends Controller
{
    public function index() {
        $services = Service::all()->map(function($service) {
            return [
                'id' => $service->id,
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'location' => $service->location,
                'contact_info' => $service->contact_info,
                'image' => $service->image_path ? asset($service->image_path) : null,
                'category_id' => $service->category_id,
                'user_id' => $service->user_id,
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ];
        });
        return response()->json(['message' => 'Services retrieved successfully', 'services' => $services], 200);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validated['image_path'] = 'images/' . $imageName;
        }

        $validated['user_id'] = Auth::id();

        $service = Service::create($validated);
        return response()->json(['message' => 'Service created successfully', 'service' => $service], 201);
    }

    public function show($id) {
        $service = Service::findOrFail($id);
        $serviceData = [
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'price' => $service->price,
            'location' => $service->location,
            'contact_info' => $service->contact_info,
            'image' => $service->image_path ? asset($service->image_path) : null,
            'category_id' => $service->category_id,
            'user_id' => $service->user_id,
            'created_at' => $service->created_at,
            'updated_at' => $service->updated_at,
        ];
        return response()->json(['message' => 'Service retrieved successfully', 'service' => $serviceData], 200);
    }

    public function update(Request $request, $id) {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'location' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $validated['image_path'] = 'images/' . $imageName;
        }

        $service->update($validated);
        $serviceData = [
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'price' => $service->price,
            'location' => $service->location,
            'contact_info' => $service->contact_info,
            'image' => $service->image_path ? asset($service->image_path) : null,
            'category_id' => $service->category_id,
            'user_id' => $service->user_id,
            'created_at' => $service->created_at,
            'updated_at' => $service->updated_at,
        ];
        return response()->json(['message' => 'Service updated successfully', 'service' => $serviceData], 200);
    }

    public function destroy($id) {
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json(['message' => 'Service deleted successfully'], 200);
    }

    public function request(Request $request, $id) {
        $service = Service::findOrFail($id);
        $user = Auth::user();

        $existingUser = ServiceRequest::where('service_id', $id)
            ->where('user_id', $user->id)
            ->first();
        if ($existingUser) {
            return response()->json(['message' => 'You have already requested this service'], 400);
        }

        $serviceRequest = ServiceRequest::create([
            'service_id' => $id,
            'user_id' => $user->id,
            'status' => 'pending',
            'request_date' => now(),
            'address' => null,
            'description' => null,
            'price' => $service->price,
        ]);

        return response()->json(['message' => 'Service requested successfully', 'service_request' => $serviceRequest], 201);

    }
}
