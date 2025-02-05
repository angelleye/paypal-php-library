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

$PayPal = new \angelleye\PayPal\rest\paymentexperience\PaymentExperianceAPI($configArray);

$ProfileID = 'TXP-293865132D411505B';       // Required. The ID of the profile for which to show details.

$returnArray = $PayPal->DeleteWebProfile($ProfileID);
echo "<pre>";
var_dump($returnArray);

