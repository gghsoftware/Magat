<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD

class DashboardController extends Controller
{
    public function index()
    {
        // You can pass data (like total users, orders, etc.) here
        return view('admin.dashboard');
=======
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Payments table capabilities
        $hasCreatedAt    = Schema::hasColumn('payments', 'created_at');
        $hasPaidAt       = Schema::hasColumn('payments', 'paid_at');
        $hasVerification = Schema::hasColumn('payments', 'verification_status');
        $hasProof        = Schema::hasColumn('payments', 'proof_path');

        // Filter (?v=...) only when verification is supported
        $filter = $hasVerification ? (string) $request->query('v', 'pending') : 'all';

        // Cards
        $stats = [
            'total_users' => User::count(),
            'orders'      => Order::count(),
            'revenue'     => (float) Payment::where('payment_status', 'completed')->sum('amount'),
            'messages'    => 23, // replace with your real count if you have a messages table
        ];

        // Payments list
        $query = Payment::with('order');
        if ($hasVerification && $filter !== 'all') {
            $query->where('verification_status', $filter);
        }
        if ($hasCreatedAt) {
            $query->orderByDesc('created_at');
        } elseif ($hasPaidAt) {
            $query->orderByDesc('paid_at');
        } else {
            $query->orderByDesc('id');
        }
        $payments = $query->limit(10)->get();

        // Monthly summary (prefer completed payments by paid_at, fallback to orders by created_at)
        if ($hasPaidAt) {
            $monthly = Payment::selectRaw("DATE_FORMAT(paid_at, '%Y-%m') AS ym, SUM(amount) AS total")
                ->where('payment_status', 'completed')
                ->whereNotNull('paid_at')
                ->groupBy('ym')->orderBy('ym')->get();
        } else {
            $monthly = Order::selectRaw("DATE_FORMAT(created_at, '%Y-%m') AS ym, SUM(subtotal) AS total")
                ->groupBy('ym')->orderBy('ym')->get();
        }

        // Badge maps
        $pMap = [
            'pending'   => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'failed'    => 'bg-red-100 text-red-800',
        ];
        $vMap = [
            'pending'  => 'bg-gray-100 text-gray-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
        ];

        return view('admin.dashboard', compact(
            'stats', 'payments', 'monthly', 'filter', 'pMap', 'vMap'
        ))->with([
            'supportsVerification' => $hasVerification,
            'supportsProof'        => $hasProof,
        ]);
>>>>>>> 54d403e (Initial commit of Magat Funeral project)
    }
}
