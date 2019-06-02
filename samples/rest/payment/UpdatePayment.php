<?php
/**
 * Partially updates a payment, by ID. 
 * You can update the amount, shipping address, invoice ID, and custom data. 
 * Note : You cannot update a payment after the payment executes.
 * 
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

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$paymentId = 'PAY-51939243WX127101ALPG4QZQ';    // The ID of the payment to update.

$patches = array();

$array1 = array(
    "operation" => "replace",                   // The operation to complete. Possible values: add, remove, replace, move, copy, test.
    "path" => "/transactions/0/amount",         // The JSON pointer to the target document location at which to complete the operation.
    "value" => array(                           // number,integer,string,boolean,null,array,object. The value to apply. The remove operation does not require a value.

        'total' => '33.00',
        'currency' => 'USD',
        'details' => array(
            'subtotal' => '17.50',
            'shipping' => '6.20',
            'tax' => '9.30'
        )
    )
);

array_push($patches, $array1);

$array2 = array(
    "operation" => "add",                                       // The operation to complete. Possible values: add, remove, replace, move, copy, test.
    "path" => "/transactions/0/item_list/shipping_address",     // The JSON pointer to the target document location at which to complete the operation.
    "value" => array(                                           // number,integer,string,boolean,null,array,object. The value to apply. The remove operation does not require a value.
        'recipient_name' => 'Gruneberg, Anna',
        'line1' => '52 N Main St',
        'city' => 'San Jose',
        "state" => "CA",
        "postal_code" => "95112",
        "country_code" => "US"
    )
);

array_push($patches, $array2);

$returnArray = $PayPal->UpdatePayment($paymentId,$patches);
echo "<pre>";
print_r($returnArray);
