<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'returned_at',
        'rental_period',
        'actual_returned_date',
        'late_fee',
        'late_fee_paid',
        'late_fee_paid_date',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
        'actual_returned_date' => 'datetime',
        'late_fee_paid_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
