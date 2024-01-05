<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * 用户登录应用程序
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return \redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * 创建用户
     */
    public function createUser(Request $request): RedirectResponse
    {
        $new_user = $request->validate([
            'email' => ['required', 'email'],
            'name' => ['required'],
            'password' => ['required']
        ]);

        User::create([
            'name' => $new_user['name'],
            'email' => $new_user['email'],
            'password' => Hash::make($new_user['password']),
        ]);

        return \redirect()->route('dashboard');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * 用户退出应用程序
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return \redirect('/');
    }
}
