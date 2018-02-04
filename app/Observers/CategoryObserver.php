<?php
namespace App\Observers;


use Cache;

class CategoryObserver
{
    public function __construct()
    {
        Cache::forget('categories');
    }

    public function updated(){
        //
    }

    public function created(){
        //
    }

    public function deleted(){

    }

}