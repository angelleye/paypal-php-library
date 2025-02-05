<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'Sandbox' => $sandbox,
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);

$PayPal = new angelleye\PayPal\rest\payouts\PayoutsAPI($configArray);

//## Payout Item ID you get when you create Mass payment single/batch.

$payoutItemId='';                  // Required. The ID of the Payout Item for which to show details.

$returnArray = $PayPal->CancelPayoutItem($payoutItemId);
echo "<pre>";
print_r($returnArray);
