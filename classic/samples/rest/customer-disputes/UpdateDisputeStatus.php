<?php

/**
 *  Updates the status of a dispute, by ID, from UNDER_REVIEW to either:
        WAITING_FOR_BUYER_RESPONSE
        WAITING_FOR_SELLER_RESPONSE
 *  This status change enables either the customer or merchant to submit evidence for the dispute. 
 *  To make this call, the dispute status must be UNDER_REVIEW. 
 *  Specify an action value in the JSON request body to indicate whether the status change enables 
 *  the customer or merchant to submit evidence: 
 *      If action is BUYER_EVIDENCE The status updates to WAITING_FOR_BUYER_RESPONSE      
 *      If action is SELLER_EVIDENCE The status updates to WAITING_FOR_SELLER_RESPONSE      
 */

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

$dispute_id  = 'PP-D-5615';   // The ID of the dispute that requires evidence.

/**
 *      If action is BUYER_EVIDENCE The status updates to WAITING_FOR_BUYER_RESPONSE      
 *      If action is SELLER_EVIDENCE The status updates to WAITING_FOR_SELLER_RESPONSE 
 *  */

$parameters = array(
    'action' => 'BUYER_EVIDENCE',   // The action. Indicates whether the state change enables the customer or merchant to submit evidence:
);

$response = $PayPal->UpdateDisputeStatus($dispute_id,$parameters);

echo "<pre>";
print_r($response);
exit;
