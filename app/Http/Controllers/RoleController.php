<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct() {
        // $this->authorizeResource(Role::class, 'role');
    }

    public function index() {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        $role = Role::create($validated);
        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    public function show($id) {
        $role = Role::findOrFail($id);
        return response()->json(['message' => 'Role retrieved successfully', 'role' => $role], 200);
    }

    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $role->update($validated);
        return response()->json(['message' => 'Role updated successfully', 'role' => $role], 200);
    }

    public function destroy($id) {
        try {
            $role = Role::findOrFail($id);
            // $this->authorize('delete', $role);
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized or Role not found'], 403);
        }
    }
}
