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

$authorizationId = ''; // Replace $authorizationid with any static Id you might already have. It will do a void on it

$returnArray = $PayPal->authorization_void($authorizationId);
echo "<pre>";
print_r($returnArray);
