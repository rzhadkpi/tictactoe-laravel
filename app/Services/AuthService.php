<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(LoginRequest $request): bool
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return true;
        }
        return false;
    }
    public function register(RegisterRequest $request): User
    {
        $user = new User();
        $user->fill($request->validated());
        $user->save();

        return $user;
    }

    public function logout(): bool
    {
        Auth::logout();
        return true;
    }

}
