<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequests;

class ServiceRequestController extends Controller
{
    public function index() {
        $serviceRequests = ServiceRequests::all();
        return response()->json(['message' => 'Service requests retrieved successfully', 'service_requests' => $serviceRequests], 200);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'user_id' => 'required|exists:users,id',
            'request_date' => 'nullable|date',
            'status' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $serviceRequests = ServiceRequests::create($validated);
        return response()->json(['message' => 'Service request created successfully', 'service_request' => $serviceRequests], 201);
    }

    public function show($id) {
        $serviceRequests = ServiceRequests::findOrFail($id);
        return response()->json(['message' => 'Service request retrieved successfully', 'service_request' => $serviceRequests], 200);
    }

    public function update(Request $request, $id) {
        $serviceRequests = ServiceRequests::findOrFail($id);

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|max:255',
            'request_date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $serviceRequests->update($validated);
        return response()->json(['message' => 'Service request updated successfully', 'service_request' => $serviceRequests], 200);
    }

    public function destroy($id) {
        $serviceRequests = ServiceRequests::findOrFail($id);
        $serviceRequests->delete();
        return response()->json(['message' => 'Service request deleted successfully'], 200);
    }
}
