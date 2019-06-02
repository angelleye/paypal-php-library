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

$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    $result = $PayPal->ExecuteAgreement($token);
    echo "<pre>";
    print_r($result);
}
else{
    echo "User Cancelled the Approval";
    exit;
}