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

$PayPal = new angelleye\PayPal\rest\payouts\PayoutsAPI($configArray);

//## Payout Item ID you get when you create Mass payment single/batch.

$payoutItemId='8NBP6Q535GXUL';                  // Required. The ID of the Payout Item for which to show details.

$returnArray = $PayPal->CancelPayoutItem($payoutItemId);
echo "<pre>";
print_r($returnArray);
