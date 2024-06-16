<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function remove(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->update([
            'role_id' => null,
            'name' => Str::random(255),
            'email' => Str::random(255),
            'google_id' => null,
            'facebook' => null,
            'password' => bcrypt(Str::random(25)),
            'status' => 'Deleted',
            'remember_token' => null,
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function ordersIndex(Request $request)
    {
        $orders = $request->user()->orders()
                     ->with('items')
                     ->orderBy('created_at', 'desc')
                     ->paginate(6); 
    
        return Inertia::render('Profile/OrderHistory', [
            'orders' => $orders
        ]);
    }

    public function orderDetail(Request $request, $orderId)
    {
        $order = $request->user()->orders()
                        ->with(['items' => function($query) {
                            $query->with(['good' => function($subQuery) {
                                $subQuery->select('id', 'name', 'image');
                            }])->select('id', 'order_id', 'good_id', 'quantity', 'price');
                        }])
                        ->where('id', $orderId)
                        ->firstOrFail();
    
        return Inertia::render('Profile/OrderHistoryDetail', [
            'order' => $order
        ]);
    }
    
    
    
    
}
