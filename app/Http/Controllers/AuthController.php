<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\URL;
use App\Notifications\VerifyEmailNotification;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|unique:users,phone',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|string|exists:roles,name',
            'address'  => 'nullable|string|max:255',
        ]);

        $role = Role::where('name', $validated['role'])->first();

        try {
            $user = User::create([
                'name'     => $validated['name'],
                'phone'    => $validated['phone'],
                'email'    => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role'     => $validated['role'],
                // 'role_id'  => $role ? $role->id : null,
                'address'  => $validated['address'] ?? null,
                'is_active' => false,
            ]);

            $signedUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );

            $user->notify(new VerifyEmailNotification($signedUrl));

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user'         => $user,
                'message'      => 'User registered successfully. Please check your email to verify it is you.',
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ], 201);

        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json(['message' => 'Registration failed'], 500);
        }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Please verify your email before logging in.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
            'message'      => 'Login successful',
        ], 200); 
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}