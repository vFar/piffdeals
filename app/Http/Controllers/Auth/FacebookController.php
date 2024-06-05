<?php


namespace App\Http\Controllers\Auth;

use Socialite;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            Log::info('Retrieved Facebook user data:', ['user' => $user]);

            $finduser = User::where('facebook_id', $user->id)
                            ->orWhere('email', $user->email)
                            ->first();

            if ($finduser) {
                Auth::login($finduser, true);
                Log::info('User logged in:', ['user' => $finduser]);
                return redirect()->intended('/');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id' => $user->id,
                ]);

                Auth::login($newUser, true);
                Log::info('New user created and logged in:', ['user' => $newUser]);
                return redirect()->intended('/');
            }
        } catch (\Exception $e) {
            Log::error('Error in Facebook authentication:', ['error' => $e->getMessage()]);
            return redirect('login')->withErrors('Authentication failed: ' . $e->getMessage());
        }
    }
}