<?php

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

$agreementId = 'I-4U6DTW95XNLS';                       // Required. The ID of the Billing Agreement for which to Suspend Billing Agreement.
$note        = 'Reactivating the agreement';           // Required. Reason for changing the state of the agreement. 

$returnArray = $PayPal->ReactivateBillingAgreement($agreementId,$note);
echo "<pre>";
print_r($returnArray);
