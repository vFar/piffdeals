<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Good;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        $type = $request->input('type', 'goods');


        try {
            $results = [];
            if ($type === 'goods') {
                $results = Good::where('name', 'like', "%{$query}%")
                                ->orWhere('sku', 'like', "%{$query}%")
                                ->get();
            } elseif ($type === 'orders') {
                $query = ltrim($query, '#'); // Remove hashtag if present
                $results = Order::where('id', 'like', "%{$query}%")
                                ->orWhere('status', 'like', "%{$query}%")
                                ->orWhereHas('user', function($q) use ($query) {
                                    $q->where('name', 'like', "%{$query}%")
                                      ->orWhere('email', 'like', "%{$query}%");
                                })
                                ->with('user')
                                ->get();
            } elseif ($type === 'customers') {
                $results = User::where('name', 'like', "%{$query}%")
                               ->orWhere('email', 'like', "%{$query}%")
                               ->get();
            } else {
                return response()->json(['message' => 'Invalid search type'], 400);
            }

            return response()->json([$type => $results]);
        } catch (\Exception $e) {
            Log::error('Search failed', [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
                'query' => $query,
                'type' => $type
            ]);
            return response()->json(['error' => 'Server error'], 500);
        }
    }
}
