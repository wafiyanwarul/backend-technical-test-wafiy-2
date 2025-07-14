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
        $response = ['status' => 'error', 'message' => '', 'data' => null];
        $statusCode = 401;

        try {
            // Check if request has a valid Bearer token
            $token = $request->bearerToken();
            if ($token && PersonalAccessToken::findToken($token)) {
                $response['message'] = 'Already authenticated. Please log out first.';
                $statusCode = 403;
                return response()->json($response, $statusCode);
            }

            // Find admin by username
            $admin = Admin::where('username', $request->username)->first();

            // Verify credentials
            if ($admin && Hash::check($request->password, $admin->password)) {
                $expirationMinutes = config('sanctum.expiration', 3600) / 60;
                $expiration = now()->addMinutes($expirationMinutes);
                $token = $admin->createToken('auth_token', ['*'], $expiration)->plainTextToken;

                $response = [
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => [
                        'token' => $token,
                        'admin' => new AdminResource($admin),
                    ],
                ];
                $statusCode = 200;
            } else {
                $response['message'] = 'Invalid username or password';
            }
        } catch (\Exception $e) {
            $response['message'] = 'Failed to login: An unexpected error occurred. ' . $e->getMessage();
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }

    public function logout(): JsonResponse
    {
        $response = ['status' => 'error', 'message' => ''];
        $statusCode = 400;

        try {
            $token = request()->bearerToken();
            if ($token) {
                $accessToken = PersonalAccessToken::findToken($token);
                if ($accessToken) {
                    $accessToken->delete();
                    $response = ['status' => 'success', 'message' => 'Logout successful'];
                    $statusCode = 200;
                } else {
                    $response['message'] = 'No valid token found for logout.';
                }
            } else {
                $response['message'] = 'No token provided for logout.';
            }
        } catch (\Exception $e) {
            $response['message'] = 'Failed to logout: An unexpected error occurred. ' . $e->getMessage();
            $statusCode = 500;
        }

        return response()->json($response, $statusCode);
    }
}
