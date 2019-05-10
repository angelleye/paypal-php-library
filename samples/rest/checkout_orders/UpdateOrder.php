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

/**
 * Updates an order with the CREATED or APPROVED status. You cannot update an order with the COMPLETED status.
 * Read more @ https://developer.paypal.com/docs/api/orders/v2/#orders_patch
 *
 */
$order_id = '1S392574DC197382E';    // The ID of the order to update.

$patch_array = array(               // Patch Request array. Read all Patch @ https://developer.paypal.com/docs/api/orders/v2/#orders_patch
    0 =>
    array(
        "op" => "replace",
        "path" => "/purchase_units/@reference_id=='default'/amount",
        "value" => array(
            'currency_code' => 'USD',
            'value' => 21.00,
            'breakdown' => array(
                'item_total' => array(
                    'value' => 17.50,
                    'currency_code' => 'USD',
                ),
                'shipping' => array(
                    'value' => 3.50,
                    'currency_code' => 'USD',
                )
            )
        )
    )
);

$returnArray = $PayPal->UpdateOrder($order_id,$patch_array);
echo "<pre>";
print_r($returnArray);
exit;