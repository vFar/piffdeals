<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Notifications\OrderCreatedNotification;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Models\User;

class AddressOrderController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $cartItems = $user->cart ? $user->cart->items()->with('good')->get() : collect();

        return Inertia::render('Checkout', ['cartItems' => $cartItems]);
    }

    public function index()
    {
        $orders = Auth::user()->orders()->with('items.good')->get();
        return view('profile.orders', compact('orders'));
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

        $user = Auth::user();

        if (!$user->cart || $user->cart->items->isEmpty()) {
            return redirect()->back()->withErrors('No cart items found.');
        }

        DB::transaction(function () use ($user, $request) {
            $address = Address::create($request->only(['name', 'phone_number', 'street', 'city', 'postal_code']));
            $total = 0;
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $address->id,
                'status' => 'Gaida',
                'order_date' => now(),
                'total' => 0 // Initialize with 0
            ]);

            foreach ($user->cart->items as $item) {
                if ($item->quantity > $item->good->stock_quantity) {
                    throw new \Exception('Not enough stock for the product ' . $item->good->name);
                }

                $total += $item->quantity * $item->good->price;
                OrderItem::create([
                    'order_id' => $order->id,
                    'good_id' => $item->good_id,
                    'quantity' => $item->quantity,
                    'price' => $item->good->price,
                ]);

                // Deduct stock quantity
                $item->good->decrement('stock_quantity', $item->quantity);
            }

            $order->update(['total' => $total]);
            $user->notify(new OrderCreatedNotification($order));

            // Cleanup: optionally clear the cart
            $user->cart->items()->delete();
            $user->cart->delete();
        });

        return redirect()->route('checkout.index')->with('success', 'Order created successfully.');
    }
}
