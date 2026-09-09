<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            $admins = Admin::all();
            foreach ($admins as $admin) {
                if ($authToken === md5($admin->id . '_evoting_secret_2026')) {
                    Auth::guard('admin')->setUser($admin);
                    return redirect()->route('admin.dashboard');
                }
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

        $admin = Admin::where('username', $credentials['username'])->first();

        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            Auth::guard('admin')->login($admin, true);

            $token = md5($admin->id . '_evoting_secret_2026');

            return redirect()->route('admin.dashboard')
                ->cookie('admin_auth_token', $token, 120, '/', null, false, false, false, 'Lax');
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

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->withoutCookie('admin_auth_token');
    }
}
