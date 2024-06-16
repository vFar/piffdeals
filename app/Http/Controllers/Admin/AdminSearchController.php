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

        Log::info('Search query received', ['query' => $query, 'type' => $type]);

        try {
            $results = [];
            if ($type === 'goods') {
                $results = Good::where('name', 'like', "%{$query}%")
                                ->orWhere('sku', 'like', "%{$query}%")
                                ->get();
                Log::info('Goods search results', ['results' => $results]);
            } elseif ($type === 'orders') {
                $query = ltrim($query, '#'); // Remove hashtag if present
                $results = Order::where('id', 'like', "%{$query}%")
                                ->orWhere('status', 'like', "%{$query}%")
                                ->with('user')
                                ->get();
                Log::info('Orders search results', ['results' => $results]);
            } elseif ($type === 'customers') {
                $results = User::where('name', 'like', "%{$query}%")
                               ->orWhere('email', 'like', "%{$query}%")
                               ->get();
                Log::info('Customers search results', ['results' => $results]);
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
