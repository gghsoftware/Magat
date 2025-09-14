<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PaymentController extends Controller
{
    // Payments list page
    public function index(Request $request)
    {
        $supportsVerification = Schema::hasColumn('payments', 'verification_status');
        $supportsProof        = Schema::hasColumn('payments', 'proof_path');
        $hasCreatedAt         = Schema::hasColumn('payments', 'created_at');
        $hasPaidAt            = Schema::hasColumn('payments', 'paid_at');

        // Filters
        $status  = $request->query('status');           // payment_status (pending/completed/failed)
        $verify  = $supportsVerification ? $request->query('v') : null; // verification_status (pending/approved/rejected)
        $q       = trim((string)$request->query('q', '')); // keyword (order id, customer name/email)
        $perPage = (int)($request->query('per_page', 10));
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;

        $query = Payment::with(['order']);

        if ($status && in_array($status, ['pending','completed','failed'])) {
            $query->where('payment_status', $status);
        }

        if ($supportsVerification && $verify && in_array($verify, ['pending','approved','rejected'])) {
            $query->where('verification_status', $verify);
        }

        if ($q !== '') {
            $query->where(function($qq) use ($q) {
                $qq->where('id', $q)
                   ->orWhereHas('order', function($oo) use ($q) {
                       $oo->where('id', $q)
                          ->orWhere('customer_name', 'like', "%{$q}%")
                          ->orWhere('customer_email', 'like', "%{$q}%");
                   });
            });
        }

        // Order by: created_at -> paid_at -> id
        if ($hasCreatedAt) {
            $query->orderByDesc('created_at');
        } elseif ($hasPaidAt) {
            $query->orderByDesc('paid_at');
        } else {
            $query->orderByDesc('id');
        }

        $payments = $query->paginate($perPage)->withQueryString();

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

        return view('admin.payments.index', compact(
            'payments', 'pMap', 'vMap', 'supportsVerification', 'supportsProof'
        ));
    }

    // Approve / verify
    public function approve(Payment $payment)
    {
        if (Schema::hasColumn('payments', 'verification_status')) {
            $payment->update([
                'verification_status' => 'approved',
                'verified_at'         => now(),
                'verified_by'         => optional(auth()->user())->id,
            ]);
            return back()->with('success', "Payment #{$payment->id} approved.");
        }

        // Fallback: mark as completed when no verification fields
        $payment->update(['payment_status' => 'completed']);
        return back()->with('success', "Payment #{$payment->id} marked as completed.");
    }

    // Reject / verify
    public function reject(Payment $payment, Request $request)
    {
        if (Schema::hasColumn('payments', 'verification_status')) {
            $payment->update([
                'verification_status' => 'rejected',
                'verification_notes'  => $request->input('notes'),
                'verified_at'         => now(),
                'verified_by'         => optional(auth()->user())->id,
            ]);
            return back()->with('error', "Payment #{$payment->id} rejected.");
        }

        // Fallback: mark as failed when no verification fields
        $payment->update(['payment_status' => 'failed']);
        return back()->with('error', "Payment #{$payment->id} marked as failed.");
    }
}
