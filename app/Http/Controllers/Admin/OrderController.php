<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Good;
use Illuminate\Support\Facades\Log;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with([
            'user',
            'address',
            'items.good'  // Ensure the Good model has the image path accessor
        ])->orderBy('created_at', 'desc')
          ->paginate(10); // Customize pagination as needed

        // Include total orders count
        $totalOrders = Order::count();

        return Inertia::render('Admin/Orders', [
            'orders' => $orders,
            'totalOrders' => $totalOrders
        ]);
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'address',
            'items.good'
        ]);

        return Inertia::render('Admin/OrderDetail', [
            'order' => $order
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Gaida,Procesā,Atcelts,Pabeigts'
        ]);

        // If the new status is 'Atcelts', revert the stock quantities
        if ($validated['status'] === 'Atcelts' && $order->status !== 'Atcelts') {
            foreach ($order->items as $item) {
                $good = Good::find($item->good_id);
                if ($good) {
                    $good->increment('stock_quantity', $item->quantity);
                }
            }
        }

        // Update the order status
        $order->update(['status' => $validated['status']]);

        return redirect()->back()->with('message', 'Order status updated successfully!');
    }

    

    public function orderNotifications()
    {
        // set_time_limit(0); // Disable the time limit for this script

        // return response()->stream(function () {
        //     $startTime = time(); // Record the start time

        //     while (true) {
        //         // Check if 25 seconds have passed since the script started
        //         if ((time() - $startTime) > 25) {
        //             break;
        //         }

        //         $lastOrder = Order::with('user')->latest()->first();
                
        //         if ($lastOrder) {
        //             echo "data: " . json_encode([
        //                 'id' => $lastOrder->id,
        //                 'status' => $lastOrder->status,
        //                 'user_name' => $lastOrder->user->name,
        //                 'created_at' => $lastOrder->created_at->toDateTimeString(),
        //             ]) . "\n\n";
        //             ob_flush();
        //             flush();
        //         }

        //         sleep(10); // Sleep for 10 seconds before sending the next update
        //     }
        // }, 200, [
        //     'Content-Type' => 'text/event-stream',
        //     'Cache-Control' => 'no-cache',
        //     'Connection' => 'keep-alive',
        // ]);
    }
    
    
    
}
