<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientPasswordResetController extends Controller
{
public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
               ? response()->json(['status' => __($status)], 200)
               : response()->json(['email' => [__($status)]], 422);
    }
}
