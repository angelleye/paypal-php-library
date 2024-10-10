<?php
require_once('../../includes/config.php');
require_once('../../autoload.php');

$paypal_config = array(
    'sandbox' => $sandbox,
    'rest_client_id' => $rest_client_id,
    'rest_client_secret' => $rest_client_secret,
);

$paypal = new \angelleye\PayPal\Rest($paypal_config);

$order_id = ''; // [Required] The ID of the order to retrieve.

try {
    $paypal_result = $paypal->get_order($order_id);

    // Output the PayPal result
    echo '<pre />';
    print_r($paypal_result);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}