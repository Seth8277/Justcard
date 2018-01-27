<?php

namespace App\Http\Controllers;

use App\Facades\Payment;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function notify(Request $request, PaymentMethod $method)
    {
        if ($trade_number = Payment::build($method)->validate($request->input())) {
            $trade = Trade::find($trade_number);
            if ($trade->order->status === Order::ORDER_PIAD)
                return response('SUCCESS', 200);
            $trade->order->status = Order::ORDER_PIAD;
        }
    }
}
