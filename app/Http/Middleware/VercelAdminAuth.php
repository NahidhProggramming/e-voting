<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Symfony\Component\HttpFoundation\Response;

class VercelAdminAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if standard Auth guard works
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // 2. Check fallback Vercel auth cookie
        $authToken = $request->cookie('admin_auth_token');
        if ($authToken) {
            try {
                $admin = Admin::first();
                if ($admin && $authToken === md5($admin->id . '_evoting_secret_2026')) {
                    Auth::guard('admin')->setUser($admin);
                    return $next($request);
                }
            } catch (\Throwable $e) {
                // Ignore DB error fallback
            }
        }

        // 3. Otherwise redirect to login
        return redirect()->route('admin.login');
    }
}
