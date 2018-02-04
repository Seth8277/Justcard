<?php

namespace App\Http\Controllers;

use App\Facades\Payment;
use App\Http\Resources\Order as OrderResource;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Trade;
use Symfony\Component\HttpFoundation\Response;
use Webpatser\Uuid\Uuid;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        if ($order->exists)
            return new OrderResource($order);
        else
            return $this->failed('订单不存在', Response::HTTP_NOT_FOUND);
    }

    public function pay(Order $order, PaymentMethod $method)
    {
        if (!$order->exists) {
            return $this->failed('订单不存在', Response::HTTP_NOT_FOUND);
        }
        $data = [
            'id' => Uuid::generate(),
            'payment_method_id' => $method->id,
        ];

        $trade = Trade::create($data);
        $params = json_decode($method->params);
        return Payment::build($method->name, $params)
            ->submit($trade->id, $order->price);
    }
}
