<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->only(['name', 'email', 'password']);

        $validator = Validator::make($data, [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        /** @var User $user */
        $user = User::create($data);

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

    public function auth(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $validator = Validator::make($credentials, [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if (Auth::attempt($credentials) === false) {
            return Response::json(['message' => 'Wrong credentials'], 401);
        }

        /** @var User $user */
        $user = User::where('email', $credentials['email'])->first();

        $user->tokens()->delete();

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

    public function logout(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        return [];
    }
}
