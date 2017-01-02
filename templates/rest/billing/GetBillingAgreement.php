<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$agreementId = '';                       // Required. The ID of the Billing Agreement for which to show details.

$returnArray = $PayPal->get_billing_agreement($agreementId);
echo "<pre>";
var_dump($returnArray);
?>