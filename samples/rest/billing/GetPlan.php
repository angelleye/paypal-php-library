<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$planId = 'P-3P439474RB082731BA3QJ56I';                       // Required. The ID of the billing plan for which to show details.

$returnArray = $PayPal->get_plan($planId);
echo "<pre>";
var_dump($returnArray);
?>