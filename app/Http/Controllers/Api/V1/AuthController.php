<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_id' => $request->tenant_id ?? null,
            'user_type' => 'customer',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function registerWithGoogle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $idToken = $request->id_token;
        $googleUser = $this->verifyGoogleIdToken($idToken);
        if (!$googleUser) {
            return response()->json(['error' => 'Invalid Google token'], 401);
        }

        $user = User::firstOrCreate([
            'email' => $googleUser['email'],
        ], [
            'name' => $googleUser['name'] ?? $googleUser['email'],
            'password' => Hash::make(Str::random(16)),
            'tenant_id' => $request->tenant_id ?? null,
            'user_type' => 'customer',
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    private function verifyGoogleIdToken($idToken)
    {
        // Minimal verification using Google API
        $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        try {
            $payload = $client->verifyIdToken($idToken);
            if ($payload) {
                return [
                    'email' => $payload['email'],
                    'name' => $payload['name'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Google ID token verification failed: ' . $e->getMessage());
        }
        return null;
    }
}
