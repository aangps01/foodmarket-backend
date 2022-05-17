<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserLoginRequest;
use App\Http\Requests\API\UserRegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        try {
            $validated = $request->validated();

            if (!Auth::attempt($validated)) {
                return ResponseFormatter::error([
                    'message' => 'Invalid credentials'
                ], 'Authentication Failed', 500);
            }

            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authentication Successfully');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something Went Wrong'
            ], 'Authentication Failed', 500);
        }
    }

    public function register(UserRegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            User::create($validated);
            $user = User::where('email', $validated['email'])->first();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Registration Successfully');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Something Went Wrong'
            ], 'Registration Failed', 500);
        }
    }
}