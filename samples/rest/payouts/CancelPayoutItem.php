<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);

$PayPal = new angelleye\PayPal\rest\payouts\PayoutsAPI($configArray);

//## Payout Item ID you get when you create Mass payment single/batch.

$payoutItemId='G25MM6DB4KLNQ';                  // Required. The ID of the Payout Item for which to show details.

$returnArray = $PayPal->cancel_payout_item($payoutItemId);
echo "<pre>";
var_dump($returnArray);
?>