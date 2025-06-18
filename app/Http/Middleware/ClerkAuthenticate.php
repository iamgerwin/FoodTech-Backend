<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\User;

class ClerkAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !Str::startsWith($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized: No token provided'], 401);
        }
        $jwt = Str::replaceFirst('Bearer ', '', $authHeader);

        // Get Clerk public keys (cached for 1 hour)
        $jwks = Cache::remember('clerk_jwks', 3600, function () {
            $jwksUrl = 'https://clerk.' . env('CLERK_JWT_DOMAIN', 'accounts.dev') . '/.well-known/jwks.json';
            $json = @file_get_contents($jwksUrl);
            if (!$json) return null;
            return json_decode($json, true);
        });
        if (!$jwks || !isset($jwks['keys'])) {
            return response()->json(['error' => 'Unable to fetch Clerk JWKS'], 500);
        }

        // Decode header to get kid
        $header = json_decode(base64_decode(explode('.', $jwt)[0]), true);
        $kid = $header['kid'] ?? null;
        $keyData = collect($jwks['keys'])->firstWhere('kid', $kid);
        if (!$keyData) {
            return response()->json(['error' => 'Invalid token: key not found'], 401);
        }
        $publicKey = $this->buildPem($keyData['n'], $keyData['e']);

        try {
            $payload = JWT::decode($jwt, new Key($publicKey, 'RS256'));
        } catch (\Exception $e) {
            Log::error('Clerk JWT decode failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Optionally sync user to local DB
        $user = User::firstOrCreate([
            'email' => $payload->email,
        ], [
            'name' => $payload->name ?? $payload->email,
            'user_type' => 'customer',
            'tenant_id' => null,
            'password' => '', // Not used
        ]);

        // Attach user to request
        $request->setUserResolver(fn () => $user);
        return $next($request);
    }

    // Helper to build PEM from n/e
    private function buildPem($n, $e)
    {
        $modulus = $this->base64url_decode($n);
        $exponent = $this->base64url_decode($e);
        $components = [
            'modulus' => $modulus,
            'publicExponent' => $exponent,
        ];
        $rsa = '';
        foreach ($components as $component) {
            $rsa .= chr(2) . $this->encodeLength(strlen($component)) . $component;
        }
        $rsa = chr(48) . $this->encodeLength(strlen($rsa)) . $rsa;
        $rsa = base64_encode($rsa);
        $pem = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($rsa, 64, "\n") . "-----END PUBLIC KEY-----\n";
        return $pem;
    }
    private function base64url_decode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
    private function encodeLength($length) {
        if ($length <= 127) {
            return chr($length);
        }
        $temp = ltrim(pack('N', $length), "\x00");
        return chr(0x80 | strlen($temp)) . $temp;
    }
}
