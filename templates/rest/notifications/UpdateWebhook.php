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

$PayPal = new \angelleye\PayPal\rest\notifications\NotificationsAPI($configArray);

$webhook_id = '';                                   // The ID of the webhook to update.

$requestData = array(
    array(
        'Op' => '',                                  // The operation to complete. Valid Values: ["add", "remove", "replace", test"]
        'Path'      => '',                           // The JSON pointer to the target document location at which to complete the operation.
        'Value'     => ''                            // The value to apply. The remove operation does not require a value.
    ),
    array(
        'Op' => '',                                  // The operation to complete. Valid Values: ["add", "remove", "replace", test"]
        'Path'      => '',                           // The JSON pointer to the target document location at which to complete the operation.
        'Value'     => ''                            // The value to apply. The remove operation does not require a value.
    )
);

$returnArray = $PayPal->UpdateWebhook($webhook_id,$requestData);
echo "<pre>";
print_r($returnArray);