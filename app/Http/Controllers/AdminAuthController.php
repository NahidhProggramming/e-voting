<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLogin(Request $request)
    {
        $authToken = $request->cookie('admin_auth_token');
        if ($authToken) {
            try {
                $admin = Admin::first();
                if ($admin && $authToken === md5($admin->id . '_evoting_secret_2026')) {
                    Auth::guard('admin')->setUser($admin);
                    return redirect()->route('admin.dashboard');
                }
            } catch (\Throwable $e) {
                // Ignore
            }
        }
        return view('admin.login');
    }

    /**
     * Handle admin login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $admin = Admin::where('username', $credentials['username'])->first();

            if ($admin && Hash::check($credentials['password'], $admin->password)) {
                Auth::guard('admin')->login($admin, true);

                $token = md5($admin->id . '_evoting_secret_2026');
                Cookie::queue('admin_auth_token', $token, 120);

                return redirect()->route('admin.dashboard');
            }
        } catch (\Throwable $e) {
            return back()->withErrors([
                'username' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ])->onlyInput('username');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang dimasukkan salah.',
        ])->onlyInput('username');
    }

    /**
     * Log the admin out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        Cookie::queue(Cookie::forget('admin_auth_token'));
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
