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

$PayPal = new \angelleye\PayPal\rest\notifications\NotificationsAPI($configArray);

$anchor_type = 'APPLICATION'; // Allowed values: APPLICATION, ACCOUNT. Default: APPLICATION. Filters the webhooks in the response by the anchor_id entity type.
$requestData['anchor_type'] = $anchor_type;

$returnArray = $PayPal->ListWebhooks($requestData);
echo "<pre>";
print_r($returnArray);