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

$PayPal = new \angelleye\PayPal\rest\customerdisputes\CustomerDisputesAPI($configArray);

$dispute_id  = '';   // The ID of the dispute for which to send a message.

$parameters = array(
    'message' => '',   // The message sent by the merchant to the other party.
);

$response = $PayPal->SendMessageToOtherParty($dispute_id,$parameters);

echo "<pre>";
print_r($response);
exit;
