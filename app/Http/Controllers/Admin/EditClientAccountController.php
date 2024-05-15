<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class EditClientAccountController extends Controller
{
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'userIds' => 'required|array',
            'status' => 'required|string|in:Aktīvs,Deaktivizēts',
        ]);

        User::whereIn('id', $validated['userIds'])
            ->update(['status' => $validated['status']]);

    }
}
