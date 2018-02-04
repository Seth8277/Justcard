<?php

namespace App\Observers;


use Cache;

class OrderObserver
{
    public function updated()
    {
        Cache::forget('categories');
    }

}