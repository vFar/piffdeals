<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Inertia\Response;




class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {   
        $siteKey = config('captcha.v2.site_key'); // Example for accessing v2 site key
        return Inertia::render('Auth/Register', [
            'recaptchaSiteKey' => $siteKey
        ]);

    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()->mixedCase()->min(8)],
                'recaptcha' => 'required' // Ensure the reCAPTCHA response is required
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));
            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        } catch (ValidationException $e) {
            // Get validation errors and format them for your Vue component
            $errors = $e->errors();

            // Use the custom language files
            $customValidationMessages = [
                'email.unique' => trans('validation.unique'),
                'password.confirmed' => trans('validation.confirmed'),
                'password.min' => trans('validation.min.string'),
                'recaptcha' => trans('validation.recaptcha'),
            ];

            // Merge the custom validation messages into the default errors

            // Example: Assuming you use Inertia.js to render your frontend
            return back()->with([
                'errors' => $errors, // Pass errors as a prop named 'errors'
            ]);
        }
    }
}
