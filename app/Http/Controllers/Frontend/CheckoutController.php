<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.cart.index')->withErrors('Your cart is empty.');
        }
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $paymentOptions = ['full', 'two', 'three']; // Full / 2 payments / 3 payments
        return view('frontend.checkout.index', compact('cart', 'subtotal', 'paymentOptions'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.cart.index')->withErrors('Your cart is empty.');
        }

        $data = $request->validate([
            'customer_name'  => ['required', 'string', 'max:120'],
            'customer_email' => ['required', 'email', 'max:120'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'payment_plan'   => ['required', Rule::in(['full', 'two', 'three'])],
            'notes'          => ['nullable', 'string', 'max:1000'],
        ]);

        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);

        DB::transaction(function () use ($data, $cart, $subtotal) {
            $orderId = DB::table('orders')->insertGetId([
                'customer_name'  => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'payment_plan'   => $data['payment_plan'],         // 'full' | 'two' | 'three'
                'subtotal'       => $subtotal,
                'status'         => 'pending',
                'notes'          => $data['notes'] ?? null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            foreach ($cart as $item) {
                DB::table('order_items')->insert([
                    'order_id'   => $orderId,
                    'product_id' => $item['id'],
                    'name'       => $item['name'],
                    'price'      => $item['price'],
                    'qty'        => $item['qty'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create payment schedule entries
            $parts = match ($data['payment_plan']) {
                'full'  => 1,
                'two'   => 2,
                'three' => 3,
                default => 1,
            };

            $per = round($subtotal / $parts, 2);
            for ($i = 1; $i <= $parts; $i++) {
                DB::table('order_payments')->insert([
                    'order_id'   => $orderId,
                    'sequence'   => $i,
                    'amount'     => $per,
                    'status'     => $i === 1 ? 'due' : 'scheduled', // first installment due immediately
                    'due_date'   => now()->addMonths($i - 1)->toDateString(), // monthly intervals
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        // clear cart
        session()->forget('cart');

        return redirect()->route('frontend.home.index')->with('status', 'Order placed! We will contact you with payment instructions.');
    }
}
