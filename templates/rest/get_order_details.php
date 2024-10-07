<?php
// Include the necessary PayPal Rest class
require_once('../../includes/config.php');
require_once('../../autoload.php');

// Set up PayPal configuration array
$paypal_config = array(
    'sandbox' => $sandbox,
    'rest_client_id' => $rest_client_id,
    'rest_client_secret' => $rest_client_secret,
);

// Instantiate the PayPal Rest object
$paypal = new angelleye\PayPal\Rest($paypal_config);

// Specify the order ID for which we want to get details
$order_id = '';  // Replace 'YOUR_ORDER_ID' with the actual order ID

try {
    // Call the get_order_details function
    $paypal_result = $paypal->get_order_details($order_id);

    // Display the response data
    echo '<pre />';
    print_r($paypal_result);

} catch (Exception $e) {
    // Handle any errors
    echo "Error: " . $e->getMessage();
}