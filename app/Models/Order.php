<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'payment_plan',    // full | two | three
        'subtotal',
        'status',          // pending | confirmed | paid | cancelled
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
    ];

    // --- Relationships ---
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scheduled/plan payments (order_payments)
    public function scheduledPayments()
    {
        return $this->hasMany(OrderPayment::class);
    }

    // Actual payment transactions (payments)
    public function transactions()
    {
        return $this->hasMany(Payment::class);
    }

    // --- Helpers ---
    public function getPrimaryItemNameAttribute(): ?string
    {
        $first = $this->items->first();
        return $first?->name;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        $like = "%{$term}%";
        return $q->where(function ($qq) use ($term, $like) {
            $qq->where('id', $term) // allow direct id search
               ->orWhere('customer_name', 'like', $like)
               ->orWhere('customer_email', 'like', $like)
               ->orWhere('customer_phone', 'like', $like)
               ->orWhere('notes', 'like', $like);
        });
    }
}
