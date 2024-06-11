<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Order;
use Inertia\Inertia;

class AddressOrderController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $cartItems = $user->cart->items()->with('good')->get();
    
        return Inertia::render('Checkout', [
            'cartItems' => $cartItems
        ]);
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
        ]);

        // Create the address first
        $address = Address::create($request->only([
            'name', 'phone_number', 'street', 'city', 'postal_code'
        ]));

        // Calculate the total from cart items if necessary
        $user = Auth::user();
        $cartItems = $user->cartItems()->with('good')->get();
        $total = $cartItems->reduce(function ($carry, $item) {
            return $carry + ($item->quantity * $item->good->price);
        }, 0);

        // Create the order with a reference to the address
        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'status' => 'pending',
            'total' => $total,
            'order_date' => now(), // Assuming you have this column
        ]);

        // Optional: attach cart items to the order if needed
        foreach ($cartItems as $cartItem) {
            $order->cartItems()->create([
                'good_id' => $cartItem->good_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->good->price,
            ]);
        }

        // Optionally clear the user's cart if the order was successfully placed
        $user->cartItems()->delete();

        return redirect()->route('checkout.index')->with('success', 'Order created successfully.');
    }
}
