<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$authorizationCaptureId = '89X135566E000560D';                       // Authorization Capture id you get from the Authorization Capture process.

$returnArray = $PayPal->get_capture($authorizationCaptureId);
echo "<pre>";
var_dump($returnArray);
?>