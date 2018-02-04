<?php

namespace App\Payment;


interface PaymentDriverInterface
{
    static function getRequiredParams(): array;

    function __construct($params);

    function submit($trade_number, $price);

    function validate($data);
}