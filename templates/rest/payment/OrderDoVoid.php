<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$orderId = '';               // OrderId From Return Object/Array When Created Payment With Paypal/ OrderGet.php

$returnArray = $PayPal->order_void($orderId);
echo "<pre>";
var_dump($returnArray);
?>