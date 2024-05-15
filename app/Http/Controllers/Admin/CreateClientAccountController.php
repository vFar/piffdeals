<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class CreateClientAccountController extends Controller
{
    /**
     * Display the registration view for admins.
     */
    public function createAdmin(): Response
    {
        return Inertia::render('Admin/Register');
    }

    /**
     * Handle an admin creating a new client user.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', Rules\Password::defaults()]
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 1,
            'status' => 'Aktīvs'
        ]);

        event(new Registered($user));

        $clientUserFetchController = new ClientUserFetchController();
        $users = $clientUserFetchController->fetchUsers(); // Fetch users via the new method

        return Inertia::render('Admin/Users', [
            'users' => $users,
            'success' => 'Client account created successfully.'
        ]);  
    }
}
