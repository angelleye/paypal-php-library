<?php

use \angelleye\PayPal\rest\checkout_orders\CheckoutOrdersAPI;

// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'LogResults' => $log_results,
    'LogPath' => $log_path,
    'LogLevel' => $log_level
);

$PayPal = new CheckoutOrdersAPI($configArray);

$authorization_id  = '3HT90723074597802';       // The PayPal-generated ID for the authorized payment to capture.

$amount = array(
    'currency_code' => 'USD',
    'value' => '5.50'
);
$response = $PayPal->Reauthorize($authorization_id,$amount);

echo "<pre>";
print_r($response);
exit;