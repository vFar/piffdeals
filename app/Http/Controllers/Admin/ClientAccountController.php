<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password; // Correct import for Password broker
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password as PasswordRules; // Correct naming for password rules
use Inertia\Inertia;


class ClientAccountController extends Controller
{
    /**
     * Send a password reset link to the given user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Log the request data
        \Log::info('Email data:', $request->only('email'));

        // Ensure the request data is an array with the 'email' key.
        $emailData = $request->only('email');

        // Send the reset link
        $status = Password::sendResetLink($emailData);

        // Log the status
        \Log::info('Password reset link status:', ['status' => $status]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Display all users with optional search filter.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $data = $this->fetchUsers($search);
        return Inertia::render('Admin/Users', [
            'users' => $data['users'],
            'totalUsers' => $data['totalUsers'],
            'filters' => $request->only('search')
        ]);
    }
    
    /**
     * Fetch users based on search criteria.
     */
    public function fetchUsers($search = '')
    {
        if (!Auth::check() || Auth::user()->role_id !== 2) {
            abort(404);
        }
    
        $users = User::with(['orders', 'orders.address'])  // Eager load orders and their addresses
                    ->where('role_id', 1)
                    ->whereIn('status', ['Aktīvs', 'Deaktivizēts'])
                    ->when($search, function ($query, $search) {
                        $query->where(function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%")
                                  ->orWhere('email', 'like', "%{$search}%");
                        });
                    })
                    ->withCount('orders')  // Count of orders directly
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
    
        // Compute the count of unique addresses per user
        foreach ($users as $user) {
            $addressIds = [];
            foreach ($user->orders as $order) {
                $addressIds[$order->address->id] = true;  // Collect unique address IDs
            }
            $user->addresses_count = count($addressIds);  // Count unique addresses
        }
    
        $totalUsers = $users->total();
    
        return [
            'users' => $users,
            'totalUsers' => $totalUsers
        ];
    }
    
    
    

    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Fetch the user's orders
        $orders = Order::where('user_id', $id)->get();
        
        // Calculate total revenue
        $totalRevenue = $orders->sum('total'); // Assuming 'total' is the column name for order total
    
        return Inertia::render('Admin/UserDetail', [
            'user' => $user,
            'orders' => $orders,
            'totalRevenue' => $totalRevenue
        ]);
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'userIds' => 'required|array',
            'status' => 'required|string|in:Aktīvs,Deaktivizēts',
        ]);

        User::whereIn('id', $validated['userIds'])
            ->update(['status' => $validated['status']]);

    }

    public function deleteUsers(Request $request)
    {
        $validated = $request->validate([
            'userIds' => 'required|array',
        ]);
    
        try {
            User::whereIn('id', $validated['userIds'])->delete();
            return redirect('/admin-users');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete users.'], 500);
        }
    }


    // Other methods remain the same...

    /**
     * Handle creating a new user account.
     */
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', PasswordRules::defaults()] // Use PasswordRules for validation
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 1,
            'status' => 'Aktīvs'
        ]);

        event(new Registered($user));

        return redirect()->route('admin-users.index')->with('success', 'Client account created successfully.');
    }
}
