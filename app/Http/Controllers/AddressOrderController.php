<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Order;

class AddressOrderController extends Controller
{
    public function create()
    {
        return Inertia::render('Checkout');
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

        $address = Address::create($request->only([
            'name', 'phone_number', 'street', 'city', 'postal_code'
        ]));

        $order = Order::create([
            'user_id' => Auth::id(),
            'address_id' => $address->id,
            'status' => 'pending',
            'total' => 0.00 // You should calculate the actual total
        ]);

        return redirect()->route('checkout.index')->with('success', 'Order created successfully.');
    }
}
