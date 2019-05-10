<?php
// Shows details for a refund, by ID.

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

$refund_id ='8U6965136S7813607';  // The ID of the refund for which to show details.

$returnArray = $PayPal->ShowRefundDetails($refund_id);
echo "<pre>";
print_r($returnArray);
