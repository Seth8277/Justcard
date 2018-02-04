<?php

namespace App\Http\Controllers;

use App\Facades\Payment;
use App\Http\Resources\PaymentDriver;
use App\Http\Resources\PaymentMethod as PaymentMethodResource;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Trade;
use DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class PaymentController extends Controller
{
    public function methods()
    {
        return PaymentMethodResource::collection(PaymentMethod::all());
    }

    public function notify(Request $request, PaymentMethod $method)
    {
        $params = json_decode($method->params);
        $trade_number = Payment::build($method, $params)
            ->validate($request->input());

        if ($trade_number) {

            $trade = Trade::find($trade_number);

            // 订单不存在或重复支付
            if (empty($trade) || !$trade->exists)
                return $this->notify_failed();

            // 重复通知
            if ($trade->order->status === Order::ORDER_PAID)
                return $this->notify_success();

            // 修改订单状态并分发卡密
            DB::transaction(function () use ($trade) {

                /** @var Product $product */
                $product = $trade->order->product();
                $order = $trade->order;

                /** @var HasMany $cards */
                $cards = $product->cards();
                $cards->take($order->number)->update(['order_id' => $order->id]);
                $trade->order->update(['status' => Order::ORDER_PAID]);
            });

            return $this->notify_success();
        }

        return $this->notify_failed();
    }

    public function store(Request $request, PaymentMethod $method)
    {
        $drivers = implode(',', $this->drivers());
        $data = $this->validate($request, [
            'name' => 'required|string',
            'driver' => "required|in:{$drivers}",
            'photo' => 'nullable|url',
            'params' => 'required|json'
        ]);

        $expected_params = Payment::getDriver($data['driver']);
        $params = json_decode($data['params']);
        if (count(array_diff_key($params, $expected_params)) > 0)
            return $this->failed('参数缺省或超出', FoundationResponse::HTTP_BAD_REQUEST);

        if ($method->fill($data)->save())
            return $this->created();
        else
            return $this->internalError();
    }

    public function driver($driver)
    {
        if (!in_array($driver, $this->drivers()))
            return $this->failed('Driver 不存在', FoundationResponse::HTTP_NOT_FOUND);
        $data = collect([
            'name' => $driver,
            'required_params' => Payment::getDriver($driver)::getRequiredParams()
        ]);
        return new PaymentDriver($data);
    }

    public function drivers()
    {
        $drivers = Payment::getDrivers();

        return array_keys($drivers);
    }

    public function notify_failed()
    {
        return response('FAIL', 500);
    }

    public function notify_success()
    {
        return response('SUCCESS', 200);
    }
}
