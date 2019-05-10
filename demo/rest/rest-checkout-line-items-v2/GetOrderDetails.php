<?php



use \angelleye\PayPal\rest\checkout_orders\CheckoutOrdersAPI;

// Include required library files.
require_once '../../../vendor/autoload.php';
require_once '../../../includes/config.php';

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
    $order_id = $_SESSION['checkout_order_id'];        // The ID of the order for which to show details.
    $response = $PayPal->GetOrderDetails($order_id);

    if($response['RESULT'] == 'Success'){
        /**
         * Get Payer info
         * For demonstration purpose , we are just taking few parameters from the payer's object.
         */

        $payer_info = array();
        $payer_info['first_name'] = isset($response['ORDER']['payer']['name']['given_name']) ? $response['ORDER']['payer']['name']['given_name'] : '';
        $payer_info['last_name'] = isset($response['ORDER']['payer']['name']['surname']) ? $response['ORDER']['payer']['name']['surname'] : '';
        $payer_info['email_address'] = isset($response['ORDER']['payer']['email_address']) ? $response['ORDER']['payer']['email_address'] : '';
        $payer_info['payer_id'] = isset($response['ORDER']['payer']['payer_id']) ? $response['ORDER']['payer']['payer_id'] : '';

        /**
         * Storing payer's object to the session to display them on next step.
         */

        $_SESSION['payer_info'] = $payer_info;

        /**
         * Get Shipping info from the payment details and set to the session
         */
        $shipping_address = array();
        $shipping_address['address_line_1'] = isset($response['ORDER']['purchase_units'][0]['shipping']['address']['address_line_1']) ? $response['ORDER']['purchase_units'][0]['shipping']['address']['address_line_1'] : '';
        $shipping_address['admin_area_2'] = isset($response['ORDER']['purchase_units'][0]['shipping']['address']['admin_area_2']) ? $response['ORDER']['purchase_units'][0]['shipping']['address']['admin_area_2'] : '';
        $shipping_address['admin_area_1'] = isset($response['ORDER']['purchase_units'][0]['shipping']['address']['admin_area_1']) ? $response['ORDER']['purchase_units'][0]['shipping']['address']['admin_area_1'] : '';
        $shipping_address['postal_code'] = isset($response['ORDER']['purchase_units'][0]['shipping']['address']['postal_code']) ? $response['ORDER']['purchase_units'][0]['shipping']['address']['postal_code'] : '';
        $shipping_address['country_code'] =isset($response['ORDER']['purchase_units'][0]['shipping']['address']['country_code']) ? $response['ORDER']['purchase_units'][0]['shipping']['address']['country_code'] : '';
        /**
         * Storing shipping_address's object to the session to display them on next step.
         */
        $_SESSION['shipping_address'] = $shipping_address;

        /**
         * At this point, we now have the buyer's shipping address available in our app.
         * We could now run the data through a shipping calculator to retrieve rate
         * information for this particular order.
         *
         * This would also be the time to calculate any sales tax you may need to
         * add to the order, as well as handling fees.
         *
         * We're going to set static values for these things in our static
         * shopping cart, and then re-calculate our grand total.
         *
         * We can do that by updating order information using UpdateOrder API.
         */

        $Shipping = 1.20;
        $Tax      = 1.30;
        $subtotal = isset($response['ORDER']['purchase_units'][0]['amount']['value']) ? $response['ORDER']['purchase_units'][0]['amount']['value'] : '';
        $total = $subtotal+$Shipping+$Tax;

        /**
         * Updating our session variables
         */
        $_SESSION['amount']['Total'] =$total;
        $_SESSION['paymentDetails'] = array(
            'item_total' => $subtotal,
            'shipping' => $Shipping,
            'tax_total' =>$Tax,
            'handling' => 0.00,
            'insurance' => 0.00,
            'shipping_discount' => 0.00
        );

        /**
         *  Calling UpdateOrder API.
         */
        $patch_array = array(               // Patch Request array. Read all Patch @ https://developer.paypal.com/docs/api/orders/v2/#orders_patch
            0 =>
                array(
                    "op" => "replace",
                    "path" => "/purchase_units/@reference_id=='default'/amount",
                    "value" => array(
                        'currency_code' => 'USD',
                        'value' => $total,
                        'breakdown' => array(
                            'item_total' => array(
                                'value' => $subtotal,
                                'currency_code' => $_SESSION['currency'],
                            ),
                            'shipping' => array(
                                'value' => $Shipping,
                                'currency_code' => $_SESSION['currency'],
                            ),
                            'tax_total' => array(
                                'value' => $Tax,
                                'currency_code' => $_SESSION['currency'],
                            )
                        )
                    )
                )
        );

        $returnArray = $PayPal->UpdateOrder($order_id,$patch_array);
        if($returnArray['RESULT'] == 'Success'){
            // put everything in session
            header('Location: review.php');
        }
        else{
            /**
             * Error page redirection
             */
            $_SESSION['rest_errors'] = true;
            $_SESSION['errors'] = $returnArray;
            header('Location: ../../error.php');
        }
    }
    else{
        /**
         * Error page redirection
         */
        $_SESSION['rest_errors'] = true;
        $_SESSION['errors'] = $response;
        header('Location: ../../error.php');
    }
    echo "<pre>";
    print_r($response);
    exit;
}
else{
    echo "Payment got cancelled.";
    exit;
}