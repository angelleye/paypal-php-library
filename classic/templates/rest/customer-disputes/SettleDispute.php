<?php

/**
 *  Note : To make this call, the dispute status must be UNDER_REVIEW.   
 */
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

$PayPal = new \angelleye\PayPal\rest\customerdisputes\CustomerDisputesAPI($configArray);

$dispute_id  = '';   // The ID of the dispute for which to accept a claim.

// The outcome of the adjudication. The allowed values are:
// SELLER_FAVOR. This will resolve the case in seller favor and outcome will be set as RESOLVED_SELLER_FAVOR
// BUYER_FAVOR. This will resolve the case in buyer favor and outcome will be set as RESOLVED_BUYER_FAVOR

$parameters = array(
    'adjudication_outcome' => '',   // The allowed values are: BUYER_FAVOR and SELLER_FAVOR The outcome of the adjudication. 
);

$response = $PayPal->SettleDispute($dispute_id,$parameters);

echo "<pre>";
print_r($response);
exit;