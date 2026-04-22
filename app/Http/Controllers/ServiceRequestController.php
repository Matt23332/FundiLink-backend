<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{
    public function index() {
        // Check authentication first
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated user'], 401);
        }

        $user = Auth::user();

        // Admin can see ALL service requests
        if ($user->role === 'Admin') {
            $serviceRequests = ServiceRequest::with(['service', 'user'])
                ->latest()
                ->get();
        } 
        // Providers can see requests for their services only
        elseif ($user->role === 'provider') {
            $serviceRequests = ServiceRequest::with(['service', 'user'])
                ->whereHas('service', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->latest()
                ->get();
        } 
        // Regular customers can only see their own requests
        else {
            $serviceRequests = ServiceRequest::with(['service', 'user'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json([
            'message' => 'Service requests retrieved successfully', 
            'service_requests' => $serviceRequests
        ], 200);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'request_date' => 'nullable|date',
            'status' => 'required|in:pending,reviewing,active,in-progress,completed,cancelled',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
        ]);

        $validated['user_id'] = Auth::id();

        if (!isset($validated['status'])) {
            $validated['status'] = 'pending';
        }

        if (!isset($validated['request_date'])) {
            $validated['request_date'] = now();
        }

        if (!isset($validated['price'])) {
            $validated['price'] = 0;
        }

        $serviceRequest = ServiceRequest::create($validated);
        return response()->json(['message' => 'Service request created successfully', 'service_request' => $serviceRequest], 201);
    }

    public function show($id) {
        $serviceRequest = ServiceRequest::with(['service', 'user'])->findOrFail($id);
        
        // Check authorization
        $user = Auth::user();
        if ($user->role !== 'Admin' && 
            $serviceRequest->user_id !== $user->id && 
            !($user->role === 'provider' && $serviceRequest->service->user_id === $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return response()->json(['message' => 'Service request retrieved successfully', 'service_request' => $serviceRequest], 200);
    }

    public function update(Request $request, $id) {
        $serviceRequest = ServiceRequest::findOrFail($id);
        
        $user = Auth::user();
        
        // Allow admins to update any request, providers to update requests for their services
        if ($user->role !== 'Admin' && 
            $serviceRequest->user_id !== $user->id && 
            !($user->role === 'provider' && $serviceRequest->service->user_id === $user->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'service_id' => 'sometimes|exists:services,id',
            'status' => 'sometimes|in:pending,reviewing,active,in-progress,completed,cancelled',
            'request_date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
        ]);

        $serviceRequest->update($validated);
        return response()->json(['message' => 'Service request updated successfully', 'service_request' => $serviceRequest], 200);
    }

    public function cancel($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated user'], 401);
        }

        $serviceRequest = ServiceRequest::findOrFail($id);
        $user = Auth::user();
        
        // Allow admins to cancel any request, users to cancel their own
        if ($user->role !== 'Admin' && $serviceRequest->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        // Only allow cancellation if not already completed or cancelled
        if (!in_array($serviceRequest->status, ['pending', 'reviewing', 'active', 'in-progress'])) {
            return response()->json([
                'error' => 'Cannot cancel a request that is already completed or cancelled'
            ], 422);
        }
        
        $serviceRequest->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Service request cancelled successfully', 'service_request' => $serviceRequest], 200);
    }

    public function destroy($id) {
        $serviceRequest = ServiceRequest::findOrFail($id);
        $user = Auth::user();
        
        // Allow admins to delete any request
        if ($user->role !== 'Admin' && $serviceRequest->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $serviceRequest->delete();
        return response()->json(['message' => 'Service request deleted successfully'], 200);
    }
}