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

$PayPal = new \angelleye\PayPal\rest\vault\CreditCardAPI($configArray);

$requestData=array(
                'credit_card_id' => ''             //Required.The credit_card_id is the ID of the stored credit card. 
            );

$returnArray = $PayPal->DeleteCreditCard($requestData);
echo "<pre>";
print_r($returnArray);
