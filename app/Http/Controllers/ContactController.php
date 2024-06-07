<?php

namespace App\Http\Controllers;

use App\Notifications\ContactFormNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Response;
use Illuminate\Support\Facades\Log;


class ContactController extends Controller
{
    public function create(): Response
    {
        $siteKey = config('captcha.v2.site_key');
        Log::info('ENV Variable:', ['CAPTCHA_KEY' => env('RECAPTCHA_V2_SITE_KEY')]);
        Log::info('Config Variable:', ['CAPTCHA_KEY' => config('captcha.v2.site_key')]);


        return Inertia::render('Contact', [
            'recaptchaSiteKey' => $siteKey
        ]);
    }
    

    public function sendMail(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'title' => 'required',
            'textMessage' => 'required',
            'recaptcha' => 'required' // Ensure the reCAPTCHA response is required
        ]);


        try {
            // Sending the notification
            Notification::route('mail', env('ADMIN_EMAIL_ADDRESS', 'admin@example.com'))
                ->notify(new ContactFormNotification($validatedData));

            return Inertia::render('Contact', [
                'message' => 'Message sent successfully!'
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Contact', [
                'errors' => ['server' => 'Unable to send email. Please try again later.']
            ]);
        }
    }
}

