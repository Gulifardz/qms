<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            return redirect('/admin/quarries');
        } else if (Auth::guard('company')->check()) {
            return redirect('/company/drivers');
        } else if (Auth::guard('quarry')->check()) {
            return redirect('/quarry/products');
        } else if (Auth::guard('checker')->check()) {
            return redirect('/checker/logs');
        } else if (Auth::guard('supermity')->check()) {
            return redirect('/supermity/logs');
        }

        return $next($request);
    }
}
