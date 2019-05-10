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

$profileID = '';                                          // Required.  The ID of the profile to update.

$patchArray = array();

$array1 = array(
    'Op'      => '',                                      // The operation to perform. Valid Values: ["add", "remove", "replace", "move", "copy", "test"]
    'Path'    => '',                                      // A JSON pointer that references a location in the target document where the operation is performed. A `string` value.
    'Value'   => '',                                      // New value to apply based on the operation.
);
array_push($patchArray,$array1);

$array2 = array(
    'Op'      => '',                                      // The operation to perform. Valid Values: ["add", "remove", "replace", "move", "copy", "test"]
    'Path'    => '',                                      // A JSON pointer that references a location in the target document where the operation is performed. A `string` value.
    'Value'   => '',                                      // New value to apply based on the operation.
);
array_push($patchArray,$array2);

$returnArray = $PayPal->PartiallyUpdateWebProfile($patchArray,$profileID);
echo "<pre>";
print_r($returnArray);

