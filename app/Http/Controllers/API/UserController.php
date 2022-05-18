<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserLoginRequest;
use App\Http\Requests\API\UserRegisterRequest;
use App\Http\Requests\API\UserUpdateProfileRequest;
use App\Http\Resources\UserResource;
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
                'user' => new UserResource($user)
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
                'user' => new UserResource($user),
            ], 'Registration Successfully');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => $e
            ], 'Registration Failed', 500);
        }
    }

    public function getUser()
    {
        try {
            $user = Auth::user();

            return ResponseFormatter::success([
                'user' => new UserResource($user)
            ], 'Successfully Retreive User Data');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => $e
            ], 'Something Went Wrong', 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ResponseFormatter::success([
            'message' => 'Token Successfuly Revoked'
        ], 'Successfully Logout');
    }

    public function updateProfile(UserUpdateProfileRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = Auth::user();
            $user->update($validated);

            return ResponseFormatter::success([
                'user' => new UserResource($user)
            ], 'Successfully Update Profile');
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'message' => $e
            ], 'Something Went Wrong', 500);
        }
    }
}
