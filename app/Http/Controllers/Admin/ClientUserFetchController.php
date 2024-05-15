<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientUserFetchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $data = $this->fetchUsers($search);
        return Inertia::render('Admin/Users', [
            'users' => $data['users'],
            'totalUsers' => $data['totalUsers'],
            'filters' => $request->only('search'), // Pass the current filters back to the component
        ]);
    }
    
    public function fetchUsers($search = '')
    {
        if (!Auth::check() || Auth::user()->role_id !== 2) {
            abort(404);
        }
    
        $users = User::query()
            ->where('role_id', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)->withQueryString();
    
        $totalUsers = User::where('role_id', 1)
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->count();
    
        return [
            'users' => $users,
            'totalUsers' => $totalUsers
        ];
    }
    
    

    public function show($id)
    {
        $user = User::findOrFail($id); // Fetch the user by ID
        return Inertia::render('Admin/UserDetail', ['user' => $user]); // Note the path change here
    }
    
}
