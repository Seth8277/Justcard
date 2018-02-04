<?php

namespace App\Models;

use App\Events\ProductDeleting;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'description', 'price', 'category_id'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function cards(){
        return $this->hasMany(Card::class);
    }

    public function stock(){
        return $this->cards()->count();
    }

    public function getPriceAttribute($value){
        return $value/100;
    }

    public function SetPriceAttribute($value){
        $price = round($value, 2) * 100;
        $this->attributes['price'] = $price;
    }
}
