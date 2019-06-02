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
$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$authorizationCaptureId = '89X135566E000560D';                       // Authorization Capture id you get from the Authorization Capture process.

$returnArray = $PayPal->GetCapture($authorizationCaptureId);
echo "<pre>";
print_r($returnArray);
