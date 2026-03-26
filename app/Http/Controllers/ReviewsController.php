<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reviews;

class ReviewsController extends Controller
{
    public function index() {
        $reviews = Reviews::all();
        return response()->json(['message' => 'Reviews retrieved successfully', 'reviews' => $reviews], 200);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Reviews::create($validated);
        return response()->json(['message' => 'Review created successfully', 'review' => $review], 201);
    }

    public function show($id) {
        $review = Reviews::findOrFail($id);
        return response()->json(['message' => 'Review retrieved successfully', 'review' => $review], 200);
    }

    public function update(Request $request, $id) {
        $review = Reviews::findOrFail($id);

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($validated);
        return response()->json(['message' => 'Review updated successfully', 'review' => $review], 200);
    }

    public function destroy($id) {
        $review = Reviews::findOrFail($id);
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully'], 200);
    }

}
