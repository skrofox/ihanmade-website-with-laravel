<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'shipping_address_id',
        'status',
        'subtotal',
        'discount_total',
        'shipping_total',
        'tax_total',
        'grand_total',
        'placed_at',
        'paid_at',
        'cancelled_at',
        'cancel_reason',
        'notes'
    ];

    protected $dates = ['placed_at', 'paid_at', 'cancelled_at'];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

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

    // Scope để lọc theo trạng thái
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope để lọc theo user
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Accessor để format trạng thái
    public function getStatusTextAttribute()
    {
        $statuses = [
            'placed' => 'Đã đặt hàng',
            'paid' => 'Đã thanh toán',
            'fulfilling' => 'Đang chuẩn bị',
            'shipped' => 'Đã giao hàng',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    // Accessor để format số tiền
    public function getFormattedGrandTotalAttribute()
    {
        return number_format($this->grand_total, 0, ',', '.') . 'đ';
    }
}
