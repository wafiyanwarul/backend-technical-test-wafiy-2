<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        // Check if request has a valid Bearer token
        $token = $request->bearerToken();
        if ($token && PersonalAccessToken::findToken($token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Already authenticated. Please log out first.',
                'data' => null,
            ], 403);
        }

        // Find admin by username
        $admin = Admin::where('username', $request->username)->first();

        // Verify credentials
        if ($admin && Hash::check($request->password, $admin->password)) {
            $expirationMinutes = config('sanctum.expiration', 3600) / 60;
            $expiration = now()->addMinutes($expirationMinutes);
            $token = $admin->createToken('auth_token', ['*'], $expiration)->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                    'admin' => new AdminResource($admin),
                ],
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid username or password',
            'data' => null,
        ], 401);
    }

    public function logout(): JsonResponse
    {
        try {
            $token = request()->bearerToken();
            if ($token) {
                $accessToken = PersonalAccessToken::findToken($token);
                if ($accessToken) {
                    $accessToken->delete(); // Hapus token spesifik dari database
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Logout successful',
                    ], 200);
                }
                return response()->json([
                    'status' => 'error',
                    'message' => 'No valid token found for logout.',
                ], 400);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'No token provided for logout.',
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout: An unexpected error occurred. ' . $e->getMessage(),
            ], 500);
        }
    }
}
