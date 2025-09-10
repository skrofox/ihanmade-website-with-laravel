<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'email', 'billing_address_id', 'shipping_address_id',
        'status', 'subtotal', 'discount_total', 'shipping_total',
        'tax_total', 'grand_total', 'placed_at', 'paid_at',
        'cancelled_at', 'cancel_reason'
    ];

    protected $dates = ['placed_at', 'paid_at', 'cancelled_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function billingAddress() 
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

}
