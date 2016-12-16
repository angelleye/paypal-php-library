<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$planid = '';                               // The ID of the billing plan to update. 

$items = array(
    'op'      => 'replace',                        // Possible values: add, remove, replace, move, copy, test. The operation to perform.
    'path'    => '/',                        // A JSON pointer. References a location in the target document where the operation is performed.
    'value'   => '',                        // Possible types: number, integer, string, boolean, null, array, object The value to apply. The remove operation does not require a value.
    'from'    => ''                         // A JSON pointer. References the location in the target document from which to move the value. Required for the move operation.
);

$returnArray = $PayPal->update_plan($planid,$parameters);
echo "<pre>";
var_dump($returnArray);
?>