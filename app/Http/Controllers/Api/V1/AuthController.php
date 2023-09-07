<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        return response()->json(['access_token' => $this->generateToken($user)]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(
                ['message' => 'Invalid credentials'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        return response()->json(['access_token' => $this->generateToken($user)]);
    }

    public function logout(Request $request): Response
    {
        $request->user()->tokens()->delete();

        return response()->noContent();
    }

    private function generateToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}
