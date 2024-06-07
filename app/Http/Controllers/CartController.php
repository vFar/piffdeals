<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function add(Request $request): RedirectResponse
    {
        $user = Auth::user();

        // Check if the user's email is verified
        if (!$user->hasVerifiedEmail()) {
            // Redirect to the email verification notice page
            return redirect()->route('verification.notice');
        }

        // Your existing logic to add item to the cart
        // ...
    }
}

