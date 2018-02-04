<?php

namespace App\Models;

use App\Events\OrderUpdated;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = ['name', 'photo', 'driver'];

    protected $dispatchesEvents = [
        'updated' => OrderUpdated::class
    ];
}
