<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->isSuperAdmin()) {
                Auth::logout();
                return back()->with('error', 'Akses ditolak. Akun Anda tidak memiliki hak akses Super Admin.');
            }

            $request->session()->regenerate();

            // Record login audit trail
            LoginLog::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip() ?? '127.0.0.1',
                'user_agent' => $request->userAgent() ?? 'Web Browser',
                'device_type' => 'WEB_BROWSER',
                'login_method' => 'WEB_PASSWORD',
                'status' => 'SUCCESS',
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->with('error', 'Email atau kata sandi yang Anda masukkan salah.')->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
