<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB; // Import DB facade for database access
use Illuminate\Support\Facades\Log;

class EnsureCartIsNotEmpty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = $request->user()->id; // Assumes you're using standard Laravel authentication
        
        // Check if the user has cart items
        $hasCartItems = DB::table('cart_items')
                          ->where('carts_id', $userId)
                          ->exists();

        Log::debug('Cart items exist: ' . $hasCartItems);

        if (!$hasCartItems) {
            return redirect('/cart');
        }

        return $next($request);
    }
}
