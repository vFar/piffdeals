<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function add(Request $request) // Pievieno preci grozam
    {
        $user = Auth::user(); // Iegūst pašreizējo lietotāju
        if (!$user->hasVerifiedEmail()) { // Pārbauda, vai lietotājam ir apstiprināts e-pasts
            return redirect()->route('verification.notice'); // Ja nē, pāradresē uz verifikācijas paziņojumu
        }
        $cart = $user->cart()->firstOrCreate(['user_id' => $user->id]); // Iegūst vai izveido grozu lietotājam
        $existingItem = $cart->items()->where('good_id', $request->input('goodId'))->first(); // Pārbauda, vai prece jau eksistē grozā
        if ($existingItem) { // Ja prece jau eksistē grozā
            if ((int) $request->input('quantity') !== $existingItem->quantity) { // Ja daudzums ir mainījies
                $existingItem->update(['quantity' => $request->input('quantity')]); // Atjaunina daudzumu
                Log::info('Quantity updated for item ID: ' . $existingItem->id); // Pieraksta logā
            }
        } else {
            $cart->items()->create([ // Ja prece nav grozā, pievieno to
                'good_id' => $request->input('goodId'),
                'quantity' => $request->input('quantity')
            ]);
        }
        return back()->with('success', 'Cart updated successfully'); // Atgriež atbildi ar veiksmīgu ziņojumu
    }
    public function getCartItems() // Iegūst groza preces
    {
        $user = Auth::user(); // Iegūst pašreizējo lietotāju
        $cartItems = $user->cart->items()->with('good')->get() ?? []; // Iegūst preces no groza ar saistītajiem labumiem
        return response()->json($cartItems); // Atgriež preces kā JSON atbildi
    }
    public function index() // Parāda groza skatu
    {
        $user = Auth::user(); // Iegūst pašreizējo lietotāju
        $cartItems = $user->cart->items()->with('good')->get() ?? []; // Iegūst preces no groza ar saistītajiem labumiem
        return Inertia::render('Cart', [
            'cartItems' => $cartItems,
        ]); // Atgriež Inertia skatu ar groza precēm
    }
    public function remove($id) // Noņem preci no groza
    {
        $user = Auth::user(); // Iegūst pašreizējo lietotāju
        $cartItem = $user->cart->items()->find($id); // Atrod preci grozā pēc ID
        if ($cartItem) { // Ja prece ir atrasta
            $cartItem->delete(); // Dzēš preci no groza
        }
        return $this->index(); // Atgriež atjauninātu groza skatu
    }
    public function updateAndCheckout(Request $request) // Atjaunina grozu un pāriet uz norēķinu
    {
        $user = Auth::user(); // Iegūst pašreizējo lietotāju
        $cart = $user->cart; // Iegūst lietotāja grozu
        foreach ($request->input('items') as $item) { // Iterē caur precēm no pieprasījuma
            $cartItem = $cart->items()->find($item['id']); // Atrod preci grozā
            if ($cartItem) { // Ja prece ir atrasta
                $cartItem->update(['quantity' => $item['quantity']]); // Atjaunina daudzumu
            }
        }
        return redirect()->route('checkout.index'); // Pāradresē uz norēķinu lapu
    }
}

