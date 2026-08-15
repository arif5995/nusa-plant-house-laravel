<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\Shipment;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'recipient_name',
        'recipient_phone',
        'shipping_address',
        'city',
        'postal_code',
        'status',
        'subtotal',
        'shipping_cost',
        'total',
        'payment_status',
        'payment_receipt',
    ];

    // Cast enum fields to string for proper handling
    protected $casts = [
        'status' => 'string',
        'payment_status' => 'string',
    ];



    // Relationships
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
