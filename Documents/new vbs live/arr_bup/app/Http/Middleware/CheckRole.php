<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && (Auth::user()->role == 'gsd_manager' || Auth::user()->role == 'gsd_dispatcher')) {
            
            // return redirect()->route('login'); // 'sec.dashboard');
            return $next($request);
        } else {
            return redirect()->route('dashboard'); 
        }

        return $next($request);
    }
}
