<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // GET /admin/orders
    public function index(Request $request)
    {
        $q       = (string) $request->query('q', '');
        $status  = (string) $request->query('status', ''); // optional filter

        $orders = Order::with(['items' => function ($qq) {
                            $qq->orderBy('id');
                        }])
                        ->when($status !== '', fn($qq) => $qq->where('status', $status))
                        ->search($q)
                        ->orderByDesc('created_at')
                        ->paginate(10)
                        ->withQueryString();

        return view('admin.orders.index', compact('orders', 'q', 'status'));
    }

    // GET /admin/orders/{order}
    public function show(Order $order)
    {
        $order->load([
            'items',
            'scheduledPayments',
            'transactions' => fn($q) => $q->orderByDesc('paid_at')->orderByDesc('id'),
        ]);

        return view('admin.orders.show', compact('order'));
    }

    // POST /admin/orders/{order}/confirm
    public function confirm(Order $order)
    {
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Cancelled order cannot be confirmed.');
        }
        $order->update(['status' => 'confirmed']);
        return back()->with('success', "Order #{$order->id} confirmed.");
    }

    // POST /admin/orders/{order}/mark-paid
    public function markPaid(Order $order)
    {
        if ($order->status === 'cancelled') {
            return back()->with('error', 'Cancelled order cannot be marked as paid.');
        }
        $order->update(['status' => 'paid']);
        return back()->with('success', "Order #{$order->id} marked as Paid.");
    }

    // POST /admin/orders/{order}/cancel
    public function cancel(Order $order)
    {
        if ($order->status === 'paid') {
            return back()->with('error', 'Paid order cannot be cancelled.');
        }
        $order->update(['status' => 'cancelled']);
        return back()->with('success', "Order #{$order->id} cancelled.");
    }

    // DELETE /admin/orders/{order}
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}
