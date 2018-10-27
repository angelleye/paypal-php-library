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
$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$planId = '';                       // Required. The ID of the billing plan for delete.

$returnArray = $PayPal->delete_plan($planId);
echo "<pre>";
print_r($returnArray);
