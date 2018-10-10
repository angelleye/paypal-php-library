<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);

$PayPal = new \angelleye\PayPal\rest\notifications\NotificationsAPI($configArray);

$webhook_id = '6PN687461P1543922';  // The ID of the webhook to delete.

// Pass data into class for processing with PayPal and load the response array into $PayPalResult

$PayPalResult = $PayPal->delete_webhook($webhook_id);


// Write the contents of the response array to the screen for demo purposes.
echo "<pre>";
print_r($PayPalResult);
