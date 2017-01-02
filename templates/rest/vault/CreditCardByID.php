<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
                'ClientID' => $rest_client_id,
                'ClientSecret' => $rest_client_secret
                );

$PayPal = new \angelleye\PayPal\rest\vault\CreditCardAPI($configArray);

$requestData=array(
                'credit_card_id' => ''             // Required. The credit_card_id is the ID of the stored credit card. 
            );

$returnArray = $PayPal->showByID($requestData);
echo "<pre>";
var_dump($returnArray);
?>