<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'product_id', 'sku', 'barcode', 'option_value', 'status',
    ];

    protected $casts = [
        'option_value' => 'array',
    ];

    protected $attributes = [
        'status' => 'active',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
    
    public function prices()
    {
        return $this->hasMany(Price::class, 'variant_id');
    }

    // Giá hiện hành của biến thể (ưu tiên trong khoảng ngày hiệu lực)
    public function currentPrice()
    {
        return $this->hasOne(Price::class, 'variant_id')
            ->activeOn()
            ->latest('valid_from');
    }

    // Accessor để hiển thị option_value dạng text
    public function getOptionValueTextAttribute()
    {
        if (!$this->option_value || !is_array($this->option_value)) {
            return 'Không có';
        }
        
        $options = [];
        foreach ($this->option_value as $key => $value) {
            $options[] = "$key: $value";
        }
        
        return implode(', ', $options);
    }

    // Mutator để tự động tạo SKU nếu không có
    public function setSkuAttribute($value)
    {
        if (empty($value)) {
            $product = $this->product;
            $baseSku = $product ? Str::slug($product->name) : 'variant';
            $random = Str::random(6);
            $this->attributes['sku'] = strtoupper($baseSku) . '-' . $random;
        } else {
            $this->attributes['sku'] = $value;
        }
    }

    // Scope để lọc theo trạng thái
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Scope để tìm kiếm theo SKU hoặc barcode
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('sku', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%");
        });
    }
}
