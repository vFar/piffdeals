<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventAdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role_id === 2) {
            return redirect('/');  // Redirect administrators away from certain routes.
        }

        return $next($request);
    }
}
