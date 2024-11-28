<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $request->session()->flash('status', 'Untuk mengakses halaman admin login dulu!');
            return redirect('login');
        }

        return $next($request);
    }
}
