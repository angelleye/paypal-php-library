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

$agreementId = 'I-4U6DTW95XNLS';  // The ID of the agreement for which to set a balance.

$amount = array(
    'Currency' => 'USD',
    'value' => '5.00'
);

$returnArray = $PayPal->SetAgreementBalance($agreementId,$amount);
echo "<pre>";
print_r($returnArray);
