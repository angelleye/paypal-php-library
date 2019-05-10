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

$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$planId = 'P-5TA8920425143812EA47LOGA';                       // Required. The ID of the billing plan for delete.

$returnArray = $PayPal->DeletePlan($planId);
echo "<pre>";
print_r($returnArray);
