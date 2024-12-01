<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedWithRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userRole = Auth::user()->role;

            // Prevent redirection loop
            if ($request->isMethod('get')) {
                if ($userRole === 'admin' && !$request->routeIs('dashboard')) {
                    return redirect()->route('dashboard');
                }

                if ($userRole === 'user' && !$request->routeIs('todos.index')) {
                    return redirect()->route('todos.index');
                }
            }
        }

        return $next($request);
    }
}
