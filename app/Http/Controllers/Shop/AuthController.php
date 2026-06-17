<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('shop.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Введіть email',
            'email.email'       => 'Невірний формат email',
            'password.required' => 'Введіть пароль',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('shop.account.orders'))
                ->with('success', 'Вітаємо, ' . Auth::user()->name . '!');
        }

        return back()->withErrors(['email' => 'Невірний email або пароль'])->withInput();
    }

    public function registerForm()
    {
        return view('shop.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required'      => 'Введіть ім\'я',
            'email.required'     => 'Введіть email',
            'email.unique'       => 'Цей email вже зареєстрований',
            'password.min'       => 'Пароль мінімум 6 символів',
            'password.confirmed' => 'Паролі не співпадають',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('shop.account.orders')
            ->with('success', 'Акаунт створено! Ласкаво просимо.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('shop.index')
            ->with('success', 'Ви вийшли з акаунту.');
    }
}
