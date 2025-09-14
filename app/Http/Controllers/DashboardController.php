<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard with their payments (status, proof, verification).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Filter payments that belong to the signed-in user.
        // We support two data shapes:
        // A) orders.user_id exists, or
        // B) orders.customer_email matches user email (fallback)
        $payments = Payment::query()
            ->with(['order'])
            ->whereHas('order', function ($q) use ($user) {
                $q->where(function ($qq) use ($user) {
                    $qq->when($this->ordersHasUserId(), fn($q3) => $q3->where('user_id', $user->id))
                       ->orWhere('customer_email', $user->email);
                });
            })
            ->latest()
            ->paginate(10);

        // Simple stats for the dashboard cards (optional)
        $stats = [
            'total_payments' => (clone $payments)->total(), // paginator total
            'approved'       => $this->countByVerification($user, 'approved'),
            'pending'        => $this->countByVerification($user, 'pending'),
            'rejected'       => $this->countByVerification($user, 'rejected'),
        ];

        // Example monthly sum (optional)
        $monthly = Payment::query()
            ->selectRaw("DATE_FORMAT(COALESCE(paid_at, created_at), '%Y-%m') as ym, SUM(amount) as total")
            ->whereHas('order', function ($q) use ($user) {
                $q->where(function ($qq) use ($user) {
                    $qq->when($this->ordersHasUserId(), fn($q3) => $q3->where('user_id', $user->id))
                       ->orWhere('customer_email', $user->email);
                });
            })
            ->groupBy('ym')
            ->orderBy('ym', 'asc')
            ->get();

        return view('frontend.dashboard', compact('payments', 'stats', 'monthly'));
    }

    /**
     * User uploads/updates proof for their own payment.
     */
    public function uploadProof(Request $request, Payment $payment)
    {
        $this->authorizeUserOwnsPayment($request->user(), $payment);

        $data = $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        // Delete old file if replacing
        if ($payment->proof_path) {
            Storage::disk('public')->delete($payment->proof_path);
        }

        $path = $request->file('proof')->store('payment-proofs', 'public');

        $payment->update([
            'proof_path'          => $path,
            'verification_status' => 'pending', // resubmitted -> back to pending
            'verification_notes'  => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'Proof uploaded. Your payment is now pending verification.');
    }

    /**
     * User removes their own proof (if allowed).
     */
    public function deleteProof(Request $request, Payment $payment)
    {
        $this->authorizeUserOwnsPayment($request->user(), $payment);

        // Optional: only allow deletion while pending
        if ($payment->verification_status !== 'pending') {
            return back()->with('error', 'You can only remove proof while verification is pending.');
        }

        if ($payment->proof_path) {
            Storage::disk('public')->delete($payment->proof_path);
        }

        $payment->update([
            'proof_path'          => null,
            'verification_status' => 'pending',
        ]);

        return back()->with('success', 'Proof removed.');
    }

    /**
     * Helpers
     */
    private function ordersHasUserId(): bool
    {
        // quick schema check once per request
        static $exists = null;
        if ($exists === null) {
            $exists = DB::getSchemaBuilder()->hasColumn('orders', 'user_id');
        }
        return $exists;
    }

    private function authorizeUserOwnsPayment($user, Payment $payment): void
    {
        $payment->loadMissing('order');

        $belongs =
            ($this->ordersHasUserId() && optional($payment->order)->user_id === $user->id)
            || (optional($payment->order)->customer_email === $user->email);

        abort_unless($belongs, 403, 'You are not allowed to modify this payment.');
    }

    private function countByVerification($user, string $status): int
    {
        return Payment::where('verification_status', $status)
            ->whereHas('order', function ($q) use ($user) {
                $q->where(function ($qq) use ($user) {
                    $qq->when($this->ordersHasUserId(), fn($q3) => $q3->where('user_id', $user->id))
                       ->orWhere('customer_email', $user->email);
                });
            })
            ->count();
    }
}
