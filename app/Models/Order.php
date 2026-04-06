<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;          // ← WAJIB ADA
use App\Models\OrderItem;
use App\Models\Shipping;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shipping_id',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'payment_confirmation_date',
        'payment_notes',
        'address',
        'shipping_cost',
        'admin_shipping_cost',
        'shipping_cost_confirmed',
        'shipping_cost_confirmed_at',
        'tracking_number',
        'estimated_delivery_date',
        'guarantee_type',
        'guarantee_number',
    ];

    protected $casts = [
        'shipping_cost_confirmed_at' => 'datetime',
        'payment_confirmation_date' => 'datetime',
        'estimated_delivery_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}


    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }
}
