<?php
namespace App\Payment;


use App\Models\Setting;
use App\Models\Settings;
use Exception;

class PaymentService
{
    protected $drivers = [];

    /**
     * @param $name
     * @return PaymentDriverInterface
     * @throws Exception
     */
    public function build($name){
        if (!isset($driver[$name])){
            throw new Exception('Driver not found');
        }

        $driver = $this->drivers[$name];
        // 初始化
        if (!$driver->isInstance() && $driver->isInstantiable()){
            $params = json_decode(Setting::find("{$name} params")->value);
            $driver = $driver->newInstance($params);
        }else{
            throw new Exception('Invalid driver');
        }

        if (!$driver instanceof PaymentDriverInterface)
            throw new Exception('Invalid driver');

        return $driver;
    }

}