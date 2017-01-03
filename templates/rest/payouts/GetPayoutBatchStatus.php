<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);

$PayPal = new angelleye\PayPal\rest\payouts\PayoutsAPI($configArray);

//## Payout Batch ID you get when you create Mass payment single/batch.

$payoutBatchId='';                  // Required. The ID of the payout batch for which to show details.

$returnArray = $PayPal->get_payout_batch_status($payoutBatchId);
echo "<pre>";
var_dump($returnArray);
?>