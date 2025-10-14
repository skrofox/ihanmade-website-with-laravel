<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'line1',
        'line2',
        'ward',
        'district',
        'city',
        'province',
        'country_code',
        'is_default_shipping',
    ];

    protected $casts = [
        'is_default_shipping' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

    public function scopeDefaultShipping($query)
    {
        return $query->where('is_default_shipping', true);
    }
}
