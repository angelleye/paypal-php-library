<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
                'ClientID' => $rest_client_id,
                'ClientSecret' => $rest_client_secret
                );

$PayPal = new \angelleye\PayPal\rest\vault\CreditCardAPI($configArray);

$requestData=array(
                'credit_card_id' => 'CARD-0VS556456D399153ELCOFKXI'             // Required. The credit_card_id is the ID of the stored credit card. 
            );
// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->showByID($requestData);
// Write the contents of the response array to the screen for demo purposes.
echo "<pre>";
print_r($PayPalResult);
