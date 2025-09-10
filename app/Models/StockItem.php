<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ware_house_id', 
        'variant_id', 
        'on_hand', 
        'reserved',
        'min_stock_level',
        'max_stock_level'
    ];

    protected $casts = [
        'on_hand' => 'integer',
        'reserved' => 'integer',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
    ];

    // Relationships
    public function warehouse()
    {
        return $this->belongsTo(WareHouse::class, 'ware_house_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Accessors
    public function getAvailableStockAttribute()
    {
        return $this->on_hand - $this->reserved;
    }

    public function getStockLevelAttribute()
    {
        if ($this->on_hand <= $this->min_stock_level) {
            return 'low';
        } elseif ($this->on_hand >= $this->max_stock_level) {
            return 'high';
        } else {
            return 'normal';
        }
    }

    public function getStockLevelTextAttribute()
    {
        $levels = [
            'low' => 'Thấp',
            'normal' => 'Bình thường',
            'high' => 'Cao'
        ];
        return $levels[$this->stock_level] ?? 'Không xác định';
    }

    // Scopes
    public function scopeLowStock($query)
    {
        return $query->whereRaw('on_hand <= min_stock_level');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('on_hand', 0);
    }

    public function scopeByWarehouse($query, $warehouseId)
    {
        return $query->where('ware_house_id', $warehouseId);
    }

    public function scopeByVariant($query, $variantId)
    {
        return $query->where('variant_id', $variantId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('variant', function($q) use ($search) {
            $q->where('sku', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%")
              ->orWhereHas('product', function($pq) use ($search) {
                  $pq->where('name', 'like', "%{$search}%");
              });
        })->orWhereHas('warehouse', function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        });
    }

    // Methods
    public function adjustStock($quantity, $type = 'add')
    {
        if ($type === 'add') {
            $this->increment('on_hand', $quantity);
        } else {
            $this->decrement('on_hand', $quantity);
        }
        $this->refresh();
    }

    public function reserveStock($quantity)
    {
        if ($this->available_stock >= $quantity) {
            $this->increment('reserved', $quantity);
            $this->refresh();
            return true;
        }
        return false;
    }

    public function releaseReservedStock($quantity)
    {
        if ($this->reserved >= $quantity) {
            $this->decrement('reserved', $quantity);
            $this->refresh();
            return true;
        }
        return false;
    }
}
