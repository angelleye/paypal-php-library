<?php
// Include required library files
require_once('../../includes/config.php');
require_once('../../autoload.php');

$paypal_config = array(
    'sandbox' => $sandbox,
    'rest_client_id' => $rest_client_id,
    'rest_client_secret' => $rest_client_secret,
    'PrintHeaders' => $print_headers
);

try {
    // Create a new PayPal Rest object
    $paypal = new angelleye\PayPal\Rest($paypal_config);

    /**
     * Prepare Purchase Units
     */
    $purchase_units = array();
    $purchase_unit = array(
        'amount' => array(
            'currency_code' => 'USD',
            'value' => '100.00'
        ),
        'description' => 'Purchase description here',
        'custom_id' => 'Custom-ID-1',  // Optional: A custom ID
        'reference_id' => 'Ref-ID-1'   // Optional: A reference ID for the purchase unit
    );
    array_push($purchase_units, $purchase_unit);

    // Prepare the final request data
    $paypal_request_data = array(
        'intent' => 'CAPTURE',  // Options: AUTHORIZE or CAPTURE
        'purchase_units' => $purchase_units  // Pass the array of purchase units here
    );

    // Create the order
    $paypal_result = $paypal->create_order($paypal_request_data);

    // Output the PayPal result
    echo '<pre />';
    print_r($paypal_result);

} catch (Exception $e) {
    // Output any errors that occur
    echo "Error: " . $e->getMessage();
}