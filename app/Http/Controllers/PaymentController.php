<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentMethod as PaymentMethodResource;
use App\Models\PaymentMethod;

class PaymentController extends Controller
{
    public function methods(){
        return PaymentMethodResource::collection(PaymentMethod::all());
    }
}
