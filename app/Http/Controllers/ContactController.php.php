<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function sendMail(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        // Determine the appropriate "from" address based on the context
        $fromAddress = 'markussdove@gmail.com'; // Example for support emails
        // You can adjust the $fromAddress based on your specific logic and context

        Mail::to('your@email.com')->send(new ContactFormMail($request->all(), $fromAddress));

        return redirect()->route('contact.success');
    }

    public function success()
    {
        return Inertia::render('ContactSuccess'); // Assuming you have a view named ContactSuccess
    }
}
