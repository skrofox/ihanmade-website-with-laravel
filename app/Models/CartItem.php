<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'variant_id',
        'quantity',
        'unit_price_snapshot'
    ];


    //Moi muc thuoc ve 1 gio hang
    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    //moi muc gan voi 1 bien the san pham
    public function variant(){
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    // Tổng tiền của mục này
    public function subtotal(): float
    {
        return $this->unit_price_snapshot * $this->quantity;
    }

}
