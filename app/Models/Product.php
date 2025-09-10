<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'brand',
        'status',
        'dim_l_mm',
        'dim_w_mm',
        'dim_h_mm'
    ];

    public function getMinPriceAttribute()
    {
        $prices = $this->variants()
            ->with('currentPrice')
            ->get()
            ->map(function ($variant) {
                return $variant->currentPrice ? $variant->currentPrice->effective_price : 0;
            })
            ->filter();
        return $prices->min();
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function getCurrentPriceAttribute()
    {
        $variant = $this->variants()->with('currentPrice')->first();
        if (!$variant || !$variant->currentPrice) {
            return null;
        }
        return $variant->currentPrice->effective_price;
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
    public function getMainImageAttribute()
    {
        return $this->images()->orderBy('position')->first();
    }
}
