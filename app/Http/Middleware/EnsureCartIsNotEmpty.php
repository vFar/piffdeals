<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnsureCartIsNotEmpty
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = $request->user()->id;

        // Properly check if the user's cart has items
        $hasCartItems = DB::table('cart_items')
                          ->join('carts', 'carts.id', '=', 'cart_items.carts_id')
                          ->where('carts.user_id', $userId)
                          ->exists();

        Log::debug('Checking cart items for user: ' . $userId . ' - Exist: ' . $hasCartItems);

        if (!$hasCartItems) {
            Log::debug('No cart items found for user: ' . $userId);
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        return $next($request);
    }
}
