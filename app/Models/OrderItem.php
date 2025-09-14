<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'price',
        'qty',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'qty'   => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
