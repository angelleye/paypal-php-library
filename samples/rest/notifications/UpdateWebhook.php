<?php
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

$PayPal = new \angelleye\PayPal\rest\notifications\NotificationsAPI($configArray);

$webhook_id = '2LM604139C742693G';                           // The ID of the webhook to update.

$requestData = array(
    array(
        'Op' => 'replace',                                   // The operation to complete. Valid Values: ["add", "remove", "replace", test"]
        'Path'      => '/url',                               // The JSON pointer to the target document location at which to complete the operation.
        'Value'     => "https://requestb.in/10ujt3c1?uniqid=". uniqid()  // The value to apply. The remove operation does not require a value.
    ),
    array(
        'Op' => 'replace',                                   // The operation to complete. Valid Values: ["add", "remove", "replace", test"]
        'Path'      => '/event_types',                       // The JSON pointer to the target document location at which to complete the operation.
        'Value'     => 'PAYMENT.SALE.REFUNDED'               // The value to apply. The remove operation does not require a value.
    )
);

$returnArray = $PayPal->update_webhook($webhook_id,$requestData);
echo "<pre>";
print_r($returnArray);