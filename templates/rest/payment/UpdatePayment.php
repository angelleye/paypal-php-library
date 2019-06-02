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

$paymentId = '';                            // The ID of the payment to update.

$patches = array();

$array1 = array(
    "operation" => "",                      // The operation to complete. Possible values: add, remove, replace, move, copy, test.
    "path" => "",                           // The JSON pointer to the target document location at which to complete the operation.
    "value" => array(                       // number,integer,string,boolean,null,array,object. The value to apply. The remove operation does not require a value.
        'total' => '',
        'currency' => '',
        'details' => array(
            'subtotal' => '',
            'shipping' => '',
            'tax' => ''
        )
    )
);

array_push($patches, $array1);

$array2 = array(
    "operation" => "",                                       // The operation to complete. Possible values: add, remove, replace, move, copy, test.
    "path" => "",                                            // The JSON pointer to the target document location at which to complete the operation.
    "value" => array(                                        // number,integer,string,boolean,null,array,object. The value to apply. The remove operation does not require a value.
        'recipient_name' => '',
        'line1' => '',
        'city' => '',
        "state" => "",
        "postal_code" => "",
        "country_code" => ""
    )
);

array_push($patches, $array2);

$returnArray = $PayPal->UpdatePayment($paymentId,$patches);
echo "<pre>";
print_r($returnArray);
