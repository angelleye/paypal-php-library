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

$authorizationId='37F85749FN9121741';                  //The Authorization ID of the payment for which to show details.

$returnArray = $PayPal->GetAuthorization($authorizationId);
echo "<pre>";
print_r($returnArray);
