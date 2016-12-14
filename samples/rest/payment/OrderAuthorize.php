<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$orderId = 'O-3E566053FX793851D';               // Replace $authorizationid with any static Id you might already have. It will do a void on it

$amount = array(
    'Currency' => 'USD',                                       //Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies. 
    'Total'    => '2.00',                                       //Required. Total amount charged from the payer to the payee. In case of a refund, this is the refunded amount to the original payer from the payee. 10 characters max with support for 2 decimal places. 
);

$returnArray = $PayPal->order_authorize($orderId,$amount);
echo "<pre>";
var_dump($returnArray);
?>