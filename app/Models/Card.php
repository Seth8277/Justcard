<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['number', 'key', 'product_id', 'order_id'];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
