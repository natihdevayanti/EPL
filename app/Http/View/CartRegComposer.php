<?php

namespace App\Http\View;

use Illuminate\View\View;
use App\Cart;
use App\CartDetail;

class CartRegComposer
{
    public function compose(View $view)
    {
        if (auth()->guard('customer')->check())
        {
            $carts_reg = Cart::where('customer_id', auth()->guard('customer')->user()->id)->first();

            if(CartDetail::where('cart_id', $carts_reg->id) == NULL) {
                $carts_reg->variant = NULL;
            } else {
                $carts_reg->with('details.variant.product');
            }

            $view->with('carts_reg', $carts_reg);
        }
    }
}