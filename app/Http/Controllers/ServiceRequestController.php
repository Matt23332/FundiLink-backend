<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequests;

class ServiceRequestController extends Controller
{
    public function index() {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated user'], 401);
        }
        $serviceRequests = ServiceRequests::with('service')
            ->where('user_id', $user->id)
            ->latest()
            ->get();
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
            'price' => 'nullable|numeric',
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

    public function cancel($id) {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated user'], 401);
        }
        $serviceRequest = ServiceRequests::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        $serviceRequest->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Service request cancelled successfully', 'service_request' => $serviceRequest], 200);
    }

    public function destroy($id) {
        $serviceRequests = ServiceRequests::findOrFail($id);
        $serviceRequests->delete();
        return response()->json(['message' => 'Service request deleted successfully'], 200);
    }
}
