<?php

use \angelleye\PayPal\rest\checkout_orders\CheckoutOrdersAPI;

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

$PayPal = new CheckoutOrdersAPI($configArray);

if (isset($_GET['success']) && $_GET['success'] == 'true') {

    /**
     * get order id after the payment approval from PayPal,
     * but if you know Order id then you can directly use $order_id function to capture the order.
     */

    /**
     * Order state must be APPROVED for capturing an order.
     */
    $order_id = isset($_GET['token']) ? $_GET['token'] : '';            // Required

    $response = $PayPal->CaptureOrder($order_id);

    echo "<pre>";
    print_r($response);
    exit;

}
else{
    echo "User Cancelled the Approval";
    exit;
}

