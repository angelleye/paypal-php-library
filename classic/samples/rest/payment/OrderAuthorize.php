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
     * but if you know Order id then you can directly use $orderId function to authorize the order.
     */

    $paymentId = $_GET['paymentId'];
    $payer_id = $_GET['PayerID'];

    $result = $PayPal->ExecutePayment($paymentId,$payer_id);
    if($result['RESULT'] == 'Success'){
        $orderId = $result['ORDER']['id'];                               // Replace $orderId with any static Id you might already have.

        $amount = array(
            'Currency' => 'USD',                                        //Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies.
            'Total'    => '2.00',                                       //Required. Total amount charged from the payer to the payee. In case of a refund, this is the refunded amount to the original payer from the payee. 10 characters max with support for 2 decimal places.
        );

        $returnArray = $PayPal->OrderAuthorize($orderId,$amount);
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

