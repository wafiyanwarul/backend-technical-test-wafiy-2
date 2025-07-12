<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        // Check if user is already authenticated
        if (Auth::guard('api')->check()) {
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
            // Generate token
            $token = $admin->createToken('auth_token')->plainTextToken;

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
}
