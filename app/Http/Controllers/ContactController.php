<?php

namespace App\Http\Controllers;

use App\Notifications\ContactFormNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function sendMail(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'title' => 'required',
            'textMessage' => 'required',
        ]);


        try {
            // Sending the notification
            Notification::route('mail', env('ADMIN_EMAIL_ADDRESS', 'admin@example.com'))
                ->notify(new ContactFormNotification($validatedData));

            return Inertia::render('Contact', [
                'message' => 'Message sent successfully!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send notification: ' . $e->getMessage());
            return Inertia::render('Contact', [
                'errors' => ['server' => 'Unable to send email. Please try again later.']
            ]);
        }
    }
}

