<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
                'ClientID' => $rest_client_id,
                'ClientSecret' => $rest_client_secret
                );

$PayPal = new \angelleye\PayPal\rest\paymentexperience\PaymentExperianceAPI($configArray);

$profileID = 'TXP-41X66969DC476211J';                   // Required. The ID of the profile to update.

$patchArray = array();

$array1 = array(
    'Op'      => 'add',                                      // The operation to perform. Valid Values: ["add", "remove", "replace", "move", "copy", "test"]
    'Path'    => '/presentation/brand_name',                                      // A JSON pointer that references a location in the target document where the operation is performed. A `string` value.
    'Value'   => 'New Brand Name',                                      // New value to apply based on the operation.
);
array_push($patchArray,$array1);

$array2 = array(
    'Op'      => 'remove',                                      // The operation to perform. Valid Values: ["add", "remove", "replace", "move", "copy", "test"]
    'Path'    => '/flow_config/landing_page_type',                                      // A JSON pointer that references a location in the target document where the operation is performed. A `string` value.
    'Value'   => '',                                      // New value to apply based on the operation.
);
array_push($patchArray,$array2);

$returnArray = $PayPal->partially_update_webprofile($patchArray,$profileID);
echo "<pre>";
var_dump($returnArray);

?>