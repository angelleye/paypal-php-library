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

$event_id  = '';     // The ID of the webhook event notification for which to show details.

$returnArray = $PayPal->EventNotificationDetails($event_id);

echo "<pre>";
print_r($returnArray);