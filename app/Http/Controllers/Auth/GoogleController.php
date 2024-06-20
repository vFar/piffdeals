<?php

namespace App\Http\Controllers\Auth;

use Socialite;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            Log::info('Retrieved Google user data:', ['user' => $user]);

            $finduser = User::where('google_id', $user->id)
                            ->orWhere('email', $user->email)
                            ->first();

            if ($finduser) {
                if (is_null($finduser->email_verified_at)) {
                    $finduser->update(['email_verified_at' => now()]);
                }

                Auth::login($finduser, true);
                Log::info('User logged in:', ['user' => $finduser]);
                return redirect()->intended('/');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'email_verified_at' => now(), 
                ]);

                Auth::login($newUser, true);
                Log::info('New user created and logged in:', ['user' => $newUser]);
                return redirect()->intended('/');
            }
        } catch (\Exception $e) {
            Log::error('Error in Google authentication:', ['error' => $e->getMessage()]);
            return redirect('login')->withErrors('Authentication failed: ' . $e->getMessage());
        }
    }
}

