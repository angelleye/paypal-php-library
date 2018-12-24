<?php

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

$agreementId = 'I-YXD6XE0K2R5N';  // The ID of the agreement for which to set a balance.

$amount = array(
    'Currency' => 'USD',
    'value' => '5.00'
);

$returnArray = $PayPal->set_agreement_balance($agreementId,$amount);
echo "<pre>";
print_r($returnArray);
