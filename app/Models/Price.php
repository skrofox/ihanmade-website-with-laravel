<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'variant_id',
        'list_priced',
        'sale_price',
        'valid_from',
        'valid_to',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // Xác định khoảng giá đang hiệu lực theo ngày hiện tại
    public function scopeActiveOn($query, $date = null)
    {
        $date = $date ?: now();
        return $query->where(function ($q) use ($date) {
            $q->whereNull('valid_from')->orWhere('valid_from', '<=', $date);
        })->where(function ($q) use ($date) {
            $q->whereNull('valid_to')->orWhere('valid_to', '>=', $date);
        });
    }

    // Giá đang áp dụng: ưu tiên sale_price nếu có, ngược lại lấy list_priced
    public function getEffectivePriceAttribute()
    {
        return $this->sale_price !== null ? $this->sale_price : $this->list_priced;
    }
}
