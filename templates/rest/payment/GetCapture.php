<?php
// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'LogResults' => $log_results, 
    'LogPath' => $log_path,
    'LogLevel' => $log_level  
);
$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$authorizationCaptureId = '';                       // Authorization Capture id you get from the Authorization Capture process.

$returnArray = $PayPal->get_capture($authorizationCaptureId);
echo "<pre>";
print_r($returnArray);
