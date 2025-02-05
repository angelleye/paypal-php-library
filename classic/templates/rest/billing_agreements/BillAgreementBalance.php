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

$agreementId = '';    // The ID of the agreement for which to bill the balance.
$note = '';           // The reason for the agreement state change.

$returnArray = $PayPal->BillAgreementBalance($agreementId,$note);

echo "<pre>";
print_r($returnArray);
