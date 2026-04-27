<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class VerifyEmailController extends Controller
{
    public function verify(Request $request, $id, $hash) {
        if (!$request->hasValidSignature()) {
            return response()->json(['message' => 'Invalid or expired verification link.'], 400);
        }

        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if (!hash_equals((string) $hash, sha1($user->email))) {
            return response()->json(['message' => 'Invalid verification link.'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email has been verified successfully.'], 200);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        $user->is_active = true;
        $user->save();
        
        return response()->json(['message' => 'Email verification was successful.'], 200);
    }
}
