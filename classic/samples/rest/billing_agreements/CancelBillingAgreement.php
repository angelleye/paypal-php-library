<?php
// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

// Create PayPal object.
$configArray = array(
    'Sandbox' => $sandbox,
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'LogResults' => $log_results, 
    'LogPath' => $log_path,
    'LogLevel' => $log_level
);

$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$agreementId = 'I-C76T8XF96HBX';                       // Required. The ID of the Billing Agreement for which to Suspend Billing Agreement.
$note        = 'Canceling the agreement.';             // Required. Reason for changing the state of the agreement. 

$returnArray = $PayPal->CancelBillingAgreement($agreementId,$note);

echo "<pre>";
print_r($returnArray);
