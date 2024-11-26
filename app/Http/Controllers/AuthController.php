<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    public function indexLogin(): View
    {
        return view('auth.login');
    }

    public function indexRegister(): View
    {
        return view('auth.register');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        if (!$this->authService->login($request)) {
            return back()->withErrors(['email' => 'Incorrect username or password']);
        }

        return redirect()->route('home.index');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->authService->register($request);

        return redirect()->route('login');
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect()->route('login');
    }
}
