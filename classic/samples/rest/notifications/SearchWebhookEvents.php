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
    'start_time'=>'2018-10-01T11:00:00Z',           // Filters the webhook event notifications in the response to those created on or after this date and time and on or before the end_time value.
    'end_time'=>'2018-10-30T11:00:00Z',             // Filters the webhook event notifications in the response to those created on or after the start_time and on or before this date and time.
    'page_size' => '10',                            // Default: 10. The number of webhook event notifications to return in the response.
    'transaction_id' => '',                         // Filters the response to a single transaction, by ID.
    'event_type' =>'VAULT.CREDIT-CARD.CREATED'      // Filters the response to a single event.
);

$returnArray = $PayPal->SearchWebhookEvents($params);

echo "<pre>";
print_r($returnArray);