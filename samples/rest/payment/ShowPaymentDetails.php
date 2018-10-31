<?php
//Shows details for a payment, by ID, that is yet completed. For example, a payment that was created, approved, or failed.

// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'LogResults' => $log_results, 
    'LogPath' => $log_path,
    'LogLevel' => $log_level  
);

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$PaymentID='PAY-51939243WX127101ALPG4QZQ';                  //The ID of the payment for which to show details.

$returnArray = $PayPal->show_payment_details($PaymentID);
echo "<pre>";
print_r($returnArray);
