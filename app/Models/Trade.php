<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function payment_method(){
        return $this->belongsTo(PaymentMethod::class);
    }
}
