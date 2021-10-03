<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    protected $guarded = [];
    
    public function variant()
    {
        return $this->belongsTo('App\ProductVariant', 'product_variant_id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}
