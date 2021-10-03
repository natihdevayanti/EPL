<?php

namespace App\Http\View;

use Illuminate\View\View;
use Cookie;

class CartUnregComposer
{
    public function compose(View $view)
    {
        $carts = $this->getCarts();
        $subtotal = collect($carts)->sum(function($q) {
            return $q['qty'] * $q['product_variant_price'];
        });
        $view->with('carts', $carts)->with('subtotal', $subtotal);
    }

    private function getCarts()
    {
        $carts = json_decode(request()->cookie('ecomm-carts'), true);
        $carts = $carts != '' ? $carts:[];
        return $carts;
    }
}