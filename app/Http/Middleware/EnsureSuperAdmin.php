<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akses ditolak. Portal ini khusus untuk Super Admin.');
        }

        return $next($request);
    }
}
