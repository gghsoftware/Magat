<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $table = 'order_payments';

    protected $fillable = [
        'order_id',
        'sequence',
        'amount',
        'status',    // scheduled|due|paid|overdue
        'due_date',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'due_date'=> 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
