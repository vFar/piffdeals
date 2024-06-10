<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = $user->cart->items()->with('good')->get() ?? [];

        return Inertia::render('Checkout', [
            'cartItems' => $cartItems,
        ]);
    }
}
