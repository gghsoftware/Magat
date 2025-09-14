<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have at least 3 orders
        $need = 3 - Order::count();
        if ($need > 0) {
            $seedOrders = [
                [
                    'customer_name'  => 'Juan Dela Cruz',
                    'customer_email' => 'juan@example.com',
                    'customer_phone' => '09171234567',
                    'payment_plan'   => 'full',
                    'subtotal'       => 24500,
                    'status'         => 'confirmed',
                    'created_at'     => now()->subDays(14),
                ],
                [
                    'customer_name'  => 'Maria Santos',
                    'customer_email' => 'maria@example.com',
                    'customer_phone' => '09181234567',
                    'payment_plan'   => 'two',
                    'subtotal'       => 18990,
                    'status'         => 'pending',
                    'created_at'     => now()->subDays(9),
                ],
                [
                    'customer_name'  => 'Jose Rizal',
                    'customer_email' => 'jose@example.com',
                    'customer_phone' => '09991234567',
                    'payment_plan'   => 'three',
                    'subtotal'       => 34990,
                    'status'         => 'confirmed',
                    'created_at'     => now()->subDays(4),
                ],
            ];
            // Create only as many as needed
            for ($i = 0; $i < $need; $i++) {
                $o = Order::create($seedOrders[$i]);
                // optional: seed a couple of items per order
                OrderItem::create([
                    'order_id' => $o->id,
                    'product_id' => null,
                    'name' => 'Package A',
                    'price' => $o->subtotal * 0.7,
                    'qty' => 1,
                    'created_at' => $o->created_at,
                ]);
                OrderItem::create([
                    'order_id' => $o->id,
                    'product_id' => null,
                    'name' => 'Add-on Service',
                    'price' => $o->subtotal * 0.3,
                    'qty' => 1,
                    'created_at' => $o->created_at,
                ]);
            }
        }

        // Re-fetch and normalize indexes
        $orders = Order::orderBy('id')->take(3)->get()->values(); // values() => 0-based contiguous keys

        // Guard: if somehow we still have < 3, reuse the first order
        $o0 = $orders[0] ?? Order::first();
        $o1 = $orders[1] ?? $o0;
        $o2 = $orders[2] ?? $o0;

        // Seed payments (uses your payments schema: id, order_id, amount, payment_method, payment_status, paid_at)
        $rows = [
            [ 'order' => $o0, 'amount' => 24500.00, 'method' => 'gcash',        'status' => 'completed', 'paid_at' => Carbon::now()->subDays(12)->setTime(10,15) ],
            [ 'order' => $o1, 'amount' =>  9990.00, 'method' => 'bank_transfer','status' => 'completed', 'paid_at' => Carbon::now()->subDays(7)->setTime(14,30) ],
            [ 'order' => $o1, 'amount' =>  9000.00, 'method' => 'bank_transfer','status' => 'pending',   'paid_at' => null ],
            [ 'order' => $o2, 'amount' => 12000.00, 'method' => 'credit_card',  'status' => 'completed', 'paid_at' => Carbon::now()->subDays(3)->setTime(11,0) ],
            [ 'order' => $o2, 'amount' => 12000.00, 'method' => 'credit_card',  'status' => 'failed',    'paid_at' => Carbon::now()->subDays(2)->setTime(9,45) ],
            [ 'order' => $o2, 'amount' => 10990.00, 'method' => 'paypal',       'status' => 'pending',   'paid_at' => null ],
        ];

        foreach ($rows as $r) {
            Payment::create([
                'order_id'       => $r['order']->id,
                'amount'         => $r['amount'],
                'payment_method' => $r['method'],
                'payment_status' => $r['status'],
                'paid_at'        => $r['paid_at'],
            ]);
        }
    }
}
