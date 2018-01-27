<?php

namespace App\Payment\Drivers;


use App\Payment\PaymentDriverInterface;
use Exception;
use Illuminate\Http\Request;
use Requests;

class Eapay implements PaymentDriverInterface
{
    protected $params = [];

    public $required_params = [
        'appid' => '应用ID',
        'key' => '应用KEY',
        'subject' => '订单标题',
        'body' => '订单描述',
        'show_url' => '商品展示页'

    ];

    public function __construct($params)
    {
        $this->params = $params;
    }

    public function submit($trade_number, $price)
    {
        //get key
        $key = $this->params['key'];
        unset($this->params['key']);
        $data = [
            'out_trade_no' => $trade_number,
            'total_fee' => $price
        ];
        $data = array_merge($data, $this->params);
        $data['key'] = $this->sign($data, $key);


        // 发送请求
        $result = Requests::post('https://api.eapay.cc/v1/order/add', $data);

        // 获取结果
        $result = json_decode($result->body);

        if ($result['status'] === true) {
            $no = $result['no'];
            return redirect("https://api.eapay.cc/v1/order/pay/no/{$no}");
        } else {
            throw new Exception($result['msg']);
        }
    }

    public function validate($data)
    {
        $sign = $data['sign'];
        unset($data['sign']);

        return $sign === $this->sign($data,$this->params['key'])? $data['out_trade_no'] : false;
    }

    // 这签名恶心到我了
    public function sign($data, $key)
    {
        if (isset($data['key'])) unset($data['key']);
        if (isset($data['sign'])) unset($data['sign']);

        // 排序
        $data = array_sort($data);

        // 拼接
        $string = "";
        foreach ($data as $key => $value) {
            if (!empty($value)) $string .= "{$key}={$value}&";
        }
        $string = "{$string}key={$key}";

        $sign = strtoupper(md5($string));

        return $sign;
    }

}