<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserLoginRequest;
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
}
