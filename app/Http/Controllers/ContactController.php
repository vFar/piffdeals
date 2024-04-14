<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Validator;

class ContactController extends Controller
{
    public function sendMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'title' => 'required',
            'textMessage' => 'required',
            'recaptcha' => 'required' // Ensure the reCAPTCHA response is required
        ]);

        $validator = Validator::make($request->all(), [
            'recaptcha' => 'required|captcha'
        ]);
    
        $toAddress = 'desmundalajeeh@gmail.com'; // Specify the recipient's email address here
    
        Mail::to($toAddress)->send(new ContactFormMail($request->all(), 'no-reply@yourdomain.com'));
    
        return redirect('/contact')->with('message', 'Ziņa veiksmīgi nosūtīta!');
    }
    

    public function success()
    {
        return Inertia::render('ContactSuccess'); // Assuming you have a view named ContactSuccess
    }
}
