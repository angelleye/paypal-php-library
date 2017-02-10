<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
                'ClientID' => $rest_client_id,
                'ClientSecret' => $rest_client_secret
                );

$PayPal = new \angelleye\PayPal\rest\paymentexperience\PaymentExperianceAPI($configArray);
// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->list_web_profiles();
// Write the contents of the response array to the screen for demo purposes.
echo "<pre>";
print_r($PayPalResult);

?>