<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Category;
use Inertia\Inertia;  // Make sure this line is correctly added


class ShareNavigationData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $activeCategories = Category::where('status', 'Aktīvs')->get(['id', 'name']);
        Inertia::share('activeCategories', $activeCategories);
        return $next($request);
    }
}
