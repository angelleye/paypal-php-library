<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$planId = 'P-9BP22380CK293492RA6JGUSI';                       // Required. The ID of the billing plan for delete.

$returnArray = $PayPal->delete_plan($planId);
echo "<pre>";
print_r($returnArray);
