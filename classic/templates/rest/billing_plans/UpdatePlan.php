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

$planid = '';                                  // Required. The ID of the billing plan to update. 

$state = '';                                   // Allowed values: CREATED, ACTIVE, INACTIVE, and DELETED. Status of the billing plan.

$items = array(
    'op'      => '',                           // Possible values: add, remove, replace, move, copy, test. The operation to perform.
    'path'    => '',                           // A JSON pointer. References a location in the target document where the operation is performed.
);

$returnArray = $PayPal->UpdatePlan($planid,$items,$state);
echo "<pre>";
print_r($returnArray);
