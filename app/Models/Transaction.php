<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
    'transaction_code',   // ✅ tambahkan ini
    'user_id',
    'product_id',
    'quantity',
    'total',
    'payment',
    'change',
    'status',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
{
    return $this->belongsTo(Order::class);
}

public function items()
{
    return $this->hasMany(TransactionItem::class);
}

protected static function booted()
{
    static::deleting(function ($transaction) {
        foreach ($transaction->items as $item) {
            $item->product->increment('stock', $item->quantity);
        }
        $transaction->items()->delete();
    });
}



}
