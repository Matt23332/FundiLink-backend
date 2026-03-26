<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoriesController extends Controller
{
    public function index() {
        $categories = Categories::all();
        return response()->json(['message' => 'Categories retrieved successfully', 'categories' => $categories], 200);
    }
    
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Categories::create($validated);
        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

    public function show($id) {
        $category = Categories::findOrFail($id);
        return response()->json(['message' => 'Category retrieved successfully', 'category' => $category], 200);
    }

    public function update(Request $request, $id) {
        $category = Categories::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);
        return response()->json(['message' => 'Category updated successfully', 'category' => $category], 200);
    }

    public function destroy($id) {
        $category = Categories::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
