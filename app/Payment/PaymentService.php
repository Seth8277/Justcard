<?php

namespace App\Payment;


use App\Models\Setting;
use App\Payment\Drivers\Eapay;
use Exception;
use ReflectionClass;

class PaymentService
{
    protected $drivers = [
        'eapay' => Eapay::class,
    ];

    /**
     * @param $name
     * @return PaymentDriverInterface
     * @throws Exception
     */
    public function build($name, $params)
    {
        if (!isset($this->drivers[$name])) {
            throw new Exception('Driver not found');
        }

        $driver = new ReflectionClass($this->drivers[$name]);
        // 初始化
        if ($driver->isInstantiable()) {
            $driver = $driver->newInstance($params);
        } else {
            throw new Exception('Invalid driver');
        }

        if (!$driver instanceof PaymentDriverInterface)
            throw new Exception('Invalid driver');

        return $driver;
    }

    public function getDriver($name){
        if (!isset($this->drivers[$name]))
            throw  new Exception('Driver not found');
        return $this->drivers[$name];
    }

    /**
     * @return array
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }


}