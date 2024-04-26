<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $data = $request->only(['name', 'email', 'password']);


        $validator = validator()->make($data, [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        /** @var User $user */
        $user = User::create($data);

        return response()->json([
            'data' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function auth(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        $validator = validator()->make($credentials, [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        if (auth()->attempt($credentials) === false) {
            return response()->json([
                'message' => 'Wrong credentials',
                'errors' => [],
            ], 401);
        }

        /** @var User $user */
        $user = User::where('email', $credentials['email'])->first();

        return response()->json([
            'data' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([], 204);
    }
}
