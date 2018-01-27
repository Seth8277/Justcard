<?php

namespace App\Payment;


interface PaymentDriverInterface
{
    function __construct($params);

    function submit($trade_number, $price);

    function validate($data);
}