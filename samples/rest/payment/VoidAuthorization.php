<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$authorizationId = '5A772513EY236593C'; // Replace $authorizationid with any static Id you might already have. It will do a void on it

$returnArray = $PayPal->authorization_void($authorizationId);
echo "<pre>";
var_dump($returnArray);
?>