<?php

use \angelleye\PayPal\rest\checkout_orders\CheckoutOrdersAPI;

// Include required library files.
require_once '../../../vendor/autoload.php';
require_once '../../../includes/config.php';

/**
 * Setup configuration for the PayPal library using vars from the config file.
 * Then load the PayPal object into $PayPal
 */

$configArray = array(
    'Sandbox' => $sandbox,
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'LogResults' => $log_results,
    'LogPath' => $log_path,
    'LogLevel' => $log_level
);

$PayPal = new CheckoutOrdersAPI($configArray);

$order_id = $_SESSION['checkout_order_id']; // The ID of the order for which to capture a payment.
$response = $PayPal->CaptureOrder($order_id);
/**
 * Check if the response is successful then pass the order object and transaction id otherwise send appropriate error message.
 */
if($response['RESULT'] == 'Success'){
    $_SESSION['order_transaction_id'] = isset($response['TRANSACTION_ID']) ? $response['TRANSACTION_ID'] : '';
    header('Location: order-complete.php');
}
else{
    /**  Error page redirection */
    $_SESSION['rest_errors'] = true;
    $_SESSION['errors'] = $response;
    header('Location: ../../error.php');
}
exit;