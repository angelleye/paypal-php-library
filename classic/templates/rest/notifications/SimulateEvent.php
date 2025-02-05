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

$params = array(
    'webhook_id'=>'',                                 // The ID of the webhook. If omitted, the URL is required.
    'url'=>'',                                        // The URL for the webhook endpoint. If omitted, the webhook ID is required.
    'event_type' => '',                               // The event name. Specify one of the subscribed events. For each request, provide only one event.
    'resource_version' => '',                         // The identifier for event type ex: 1.0/2.0 etc.
);

$returnArray = $PayPal->SimulateEvent($params);

echo "<pre>";
print_r($returnArray);