<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'session_id', 'note'];


    //Mot gio hang co the co nhieu san pham
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    //tong tien gio hang
    public function total(){
        return $this->items->sum(function($item){
            return $item->unit_price_snapshot * $item->quantity;
        });
    }

    //Kiem tra gio hang co rong khong
    public function isEmpty(){
        return $this->items->count() === 0;
    }
}
