<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$authorizationId='70W61014MU457634L';                  //The Authorization ID of the payment for which to show details.

$returnArray = $PayPal->get_authorization($authorizationId);
echo "<pre>";
var_dump($returnArray);
?>