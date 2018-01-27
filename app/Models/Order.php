<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const ORDER_UNPAID = 0,
        ORDER_PIAD = 1,
        ORDER_CLOSED = -1;

    public $incrementing = false;

    public $fillable = ['product_id', 'user_id', 'number', 'price', 'status'];

    public function product()
    {
        if ($this->order_id == null)
            return null;

        return $this->belongsTo(Product::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function user()
    {
        if ($this->user_id == null)
            return null;

        return $this->belongsTo(User::class);
    }

    public function trades(){
        return $this->hasMany(Trade::class);
    }

    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    public function SetPriceAttribute($value)
    {
        $price = round($value, 2) * 100;
        $this->attributes['price'] = $price;
    }


}
