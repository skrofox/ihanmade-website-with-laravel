<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WareHouse extends Model
{
    use SoftDeletes;

    protected $fillable = ['code', 'name', 'address'];

    protected $casts = [
        'address' => 'array',
    ];

    protected $dates = ['deleted_at'];

    public function stockItems()
    {
        return $this->hasMany(StockItem::class);
    }

    // Accessor để hiển thị địa chỉ dạng text
    public function getAddressTextAttribute()
    {
        if (!$this->address || !is_array($this->address)) {
            return 'Không có địa chỉ';
        }
        
        $addressParts = [];
        if (!empty($this->address['street'])) {
            $addressParts[] = $this->address['street'];
        }
        if (!empty($this->address['city'])) {
            $addressParts[] = $this->address['city'];
        }
        if (!empty($this->address['state'])) {
            $addressParts[] = $this->address['state'];
        }
        if (!empty($this->address['zip_code'])) {
            $addressParts[] = $this->address['zip_code'];
        }
        if (!empty($this->address['country'])) {
            $addressParts[] = $this->address['country'];
        }
        
        return empty($addressParts) ? 'Không có địa chỉ' : implode(', ', $addressParts);
    }

    // Scope để tìm kiếm
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('code', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%");
        });
    }

    // Scope để lọc theo trạng thái
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeTrashed($query)
    {
        return $query->onlyTrashed();
    }

    // Method để tạo mã warehouse tự động
    public static function generateCode()
    {
        $lastWarehouse = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastWarehouse ? intval(substr($lastWarehouse->code, 3)) : 0;
        $newNumber = $lastNumber + 1;
        
        return 'WH' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}
