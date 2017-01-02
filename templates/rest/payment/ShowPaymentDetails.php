<?php
//Shows details for a payment, by ID, that is yet completed. For example, a payment that was created, approved, or failed.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$PaymentID='';                  //The ID of the payment for which to show details.

$returnArray = $PayPal->show_payment_details($PaymentID);
echo "<h3>Payment State : ".$returnArray->state." </h3>";
echo "<pre>";
var_dump($returnArray);
?>