<?php
// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'Sandbox' => $sandbox,
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'LogResults' => $log_results, 
    'LogPath' => $log_path,
    'LogLevel' => $log_level  
);
$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);


if (isset($_GET['success']) && $_GET['success'] == 'true') {

    /**
     * execute the payment if you don't know OrderID and only knows payment id and payment state is just created
     * but if you know Order id then you can directly use $orderId function to void the order.
     */

    $paymentId = $_GET['paymentId'];
    $payer_id = $_GET['PayerID'];
    $result = $PayPal->ExecutePayment($paymentId,$payer_id);

    if($result['RESULT'] == 'Success'){
        $orderId = $result['ORDER']['id'];               // OrderId From Return Object/Array When Created Payment With Paypal/ OrderGet.php

        $returnArray = $PayPal->OrderDoVoid($orderId);
        echo "<pre>";
        print_r($returnArray);
    }
    else{
        echo "<pre>";
        print_r($result);
    }

}
else{
    echo "User Cancelled the Approval";
    exit;
}
