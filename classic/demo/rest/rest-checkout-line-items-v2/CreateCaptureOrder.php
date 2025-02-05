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

/**
 * Here we are setting up the parameters for a basic Checkout flow.
 *
 * The template provided at /vendor/angelleye/paypal-php-library/templates/rest/checkout_orders/CreateCaptureOrder.php
 * contains a lot more parameters that we aren't using here, so I've removed them to keep this clean.
 */

/**
 * Get data that are previously stored in session and create an order request.
 */
$intent = 'CAPTURE';
$currency = $_SESSION['currency'];
$orderItems = $_SESSION['orderItems'];

$amount = array(
    'currency_code' => $currency,
    'value' => $_SESSION['amount']['Total'],
    'breakdown' => array(
        'item_total' => array(          // The subtotal for all items.
            'value' => $_SESSION['paymentDetails']['item_total'],
            'currency_code' => $currency
        ),
        'shipping' => array(            // The shipping fee for all items.
            'value' => $_SESSION['paymentDetails']['shipping'],
            'currency_code' => $currency
        ),
        'handling' => array(            // The handling fee for all items.
            'value' => $_SESSION['paymentDetails']['handling'],
            'currency_code' => $currency
        ),
        'tax_total' => array(            // The total tax for all items.
            'value' =>$_SESSION['paymentDetails']['tax_total'],
            'currency_code' => $currency
        ),
        'insurance' => array(            // The insurance fee for all items.
            'value' =>$_SESSION['paymentDetails']['insurance'],
            'currency_code' => $currency
        ),
        'shipping_discount' => array(    // The shipping discount for all items.
            'value' =>$_SESSION['paymentDetails']['shipping_discount'],
            'currency_code' => $currency
        )
    )
);

$application_context = array(
    'brand_name' => 'AngellEye INC',              // The label that overrides the business name in the PayPal account on the PayPal site.
    'locale' => 'en-US',                          // PayPal supports a five-character code.
    'landing_page' => 'LOGIN',                    // Allowed Values : LOGIN,BILLING
    'shipping_preferences' => 'GET_FROM_FILE',    // Allowed Values : GET_FROM_FILE , NO_SHIPPING , SET_PROVIDED_ADDRESS
    'user_action' => 'CONTINUE',                  // Configures a Continue or Pay Now checkout flow.
    'payment_method' => array(
        'payer_selected' => 'PAYPAL',                   // Values : PAYPAL,PAYPAL_CREDIT. The customer and merchant payment preferences.
        'payee_preferred' => 'UNRESTRICTED'             // Values : UNRESTRICTED , IMMEDIATE_PAYMENT_REQUIRED
    ),
    'cancel_url' => $domain.'demo/rest/rest-checkout-line-items-v2/index.php?success=false',
    'return_url' => $domain.'demo/rest/rest-checkout-line-items-v2/GetOrderDetails.php?success=true'
);

$purchase_units  = array(
    'reference_id' => 'default',            // The ID for the purchase unit. Required for multiple purchase_units or if an order must be updated by using PATCH. If you omit the reference_id for an order with one purchase unit, PayPal sets the reference_id to default.
    'description' => 'Leather Goods',       // The purchase description. Maximum length: 127.
    'custom_id' => 'CUST-PayPalFashions',   // The API caller-provided external ID. Used to reconcile client transactions with PayPal transactions. Appears in transaction and settlement reports but is not visible to the payer.
    'soft_descriptor' => 'PayPalFashions',  // The payment descriptor on the payer's credit card statement. Maximum length: 22.
    'invoice_id' => 'AEINV-'.rand(0,1000),  // The API caller-provided external invoice number for this order. Appears in both the payer's transaction history and the emails that the payer receives. Maximum length: 127.
    'amount' => $amount,
    'items' => $orderItems
);

/**
 * Now we gather all of the data above into a single array.
 */

$requestArray = array(
    'intent'=>$intent,
    'application_context' => $application_context,
    'purchase_units' => $purchase_units,
);

/**
 * Here we are making the call to the CreateOrder function in the library,
 * and we're passing in our $requestArray that we just set above.
 */

$response = $PayPal->CreateOrder($requestArray);

if($response['RESULT'] == 'Success'){
    $_SESSION['checkout_order_id'] = isset($response['ORDER']['id']) ? $response['ORDER']['id'] : '';
    header('Location: ' . $response['APPROVAL_LINK']);
}
else{
    /**
     * Error page redirection
     */
    $_SESSION['rest_errors'] = true;
    $_SESSION['errors'] = $response;
    header('Location: ../../error.php');
}
exit;