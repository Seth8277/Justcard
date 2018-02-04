<?php

namespace App\Observers;


use App\Models\Product;
use Cache;

class ProductObserver
{
    public function __construct()
    {
        Cache::forget('categories');
    }

    public function created(){
        //
    }

    public function updated(){
        //
    }

    public function deleting(Product $product){
        $orders = $product->orders;
        $orders->update(['product_id' => null]);
    }
}