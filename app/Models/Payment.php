<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $order_id
 * @property string $amount
 * @property string $payment_method   // cash|credit_card|bank_transfer|gcash|paypal
 * @property string $payment_status   // pending|completed|failed
 * @property Carbon|null $paid_at
 *
 * Optional columns (if you later add proof/verification):
 * @property string|null $proof_path
 * @property string|null $verification_status  // pending|approved|rejected
 * @property int|null $verified_by
 * @property Carbon|null $verified_at
 * @property string|null $verification_notes
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'payment_status',
        'paid_at',

        // if you add verification later:
        'proof_path',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'amount'     => 'decimal:2',
        'paid_at'    => 'datetime',
        'verified_at'=> 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Approver relation if you use an Admin model and add verification columns later
    public function verifier()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    /** Scopes */
    public function scopeStatus($q, string $status)
    {
        return $q->where('payment_status', $status);
    }

    public function scopeMethod($q, string $method)
    {
        return $q->where('payment_method', $method);
    }

    /** Accessors */
    public function isCompleted(): bool
    {
        return $this->payment_status === 'completed';
    }

    // Safe proof URL accessor (works even if you haven't added the column yet)
    public function getProofUrlAttribute(): ?string
    {
        $path = $this->getAttribute('proof_path');
        return $path ? asset('storage/'.$path) : null;
    }
}
