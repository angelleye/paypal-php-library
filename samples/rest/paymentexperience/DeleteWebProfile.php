<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
                'ClientID' => $rest_client_id,
                'ClientSecret' => $rest_client_secret
                );

$PayPal = new \angelleye\PayPal\rest\paymentexperience\PaymentExperianceAPI($configArray);

$ProfileID = 'TXP-293865132D411505B';       // Required. The ID of the profile for which to show details.

$returnArray = $PayPal->delete_web_profile($ProfileID);
echo "<pre>";
var_dump($returnArray);

?>