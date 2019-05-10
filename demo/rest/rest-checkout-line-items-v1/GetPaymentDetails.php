<?php

/* Include required library files. */

require_once('../../../vendor/autoload.php');
require_once('../../../includes/config.php');

/**
 *  #Execute Payment
 *  This is the second step required to complete PayPal checkout.
 *  Once user completes the payment, paypal redirects the browser to "redirectUrl" provided in the request.
 *  This sample will show you how to execute the payment that has been approved by the buyer by logging into paypal site.
 *  You can optionally update transaction information by passing in one or more transactions.
 *  API used: POST '/v1/payments/payment/<payment-id>/execute'.
 */


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

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);


// ### Approval Status
// Determine if the user approved the payment or not
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $paymentId = $_GET['paymentId'];
    $payer_id = $_GET['PayerID'];
    $_SESSION['payment_id'] = $paymentId;

    /** ### Optional Changes to Amount
    If you wish to update the amount that you wish to charge the customer,
    based on the shipping address or any other reason, you could
    do that by passing the transaction object with just `amount` field in it.
    Please run the example with $details object in  samples\rest\payment\ExecutePayment.php in our library
     */

    $returnArray = $PayPal->ShowPaymentDetails($paymentId);

    if($returnArray['RESULT'] == 'Success'){

        /**
         * Get Payer info
         * For demonstration purpose , we are just taking few parameters from the payer's object.
         * You can get more data of the like payer like birth_date,tax_id,tax_id_type...
         */

        $payer_info = array();
        $payer_info['suffix'] = isset($returnArray['PAYMENT']['payer']['payer_info']['suffix']) ? $returnArray['PAYMENT']['payer']['payer_info']['suffix'] : '';
        $payer_info['first_name'] = isset($returnArray['PAYMENT']['payer']['payer_info']['first_name']) ? $returnArray['PAYMENT']['payer']['payer_info']['first_name'] : '';
        $payer_info['middle_name'] = isset($returnArray['PAYMENT']['payer']['payer_info']['middle_name']) ? $returnArray['PAYMENT']['payer']['payer_info']['middle_name'] : '';
        $payer_info['last_name'] = isset($returnArray['PAYMENT']['payer']['payer_info']['last_name']) ? $returnArray['PAYMENT']['payer']['payer_info']['last_name'] : '';
        $payer_info['payer_id'] = isset($returnArray['PAYMENT']['payer']['payer_info']['payer_id']) ? $returnArray['PAYMENT']['payer']['payer_info']['payer_id'] : '';
        $payer_info['email'] = isset($returnArray['PAYMENT']['payer']['payer_info']['email']) ? $returnArray['PAYMENT']['payer']['payer_info']['email'] : '';

        /**
         * Storing payer's object to the session to display them on next step.
         */

        $_SESSION['payer_info'] = $payer_info;

        /**
         * Get Shipping info from the payment details and set to the session
         */
        $shipping_address = array();
        $shipping_address['line1'] = isset($returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['line1']) ? $returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['line1'] : '';
        $shipping_address['line2'] = isset($returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['line2']) ? $returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['line2'] : '';
        $shipping_address['city'] = isset($returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['city']) ? $returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['city'] : '';
        $shipping_address['state'] = isset($returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['state']) ? $returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['state'] : '';
        $shipping_address['postal_code'] = isset($returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['postal_code']) ? $returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['postal_code'] : '';
        $shipping_address['country_code'] = isset($returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['country_code']) ? $returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['country_code'] : '';
        $shipping_address['phone'] = isset($returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['phone']) ? $returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['phone'] : '';
        $shipping_address['type'] = isset($returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['type']) ? $returnArray['PAYMENT']['payer']['payer_info']['shipping_address']['type'] : '';

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
         * We can do that by passing the transaction object with just `amount` field in it.
         */

        $_SESSION['paymentDetails'] = array(
            'Subtotal' => isset($_SESSION['amount']['Total']) ? number_format($_SESSION['amount']['Total'],2) : '',
            'Shipping' => '1.20',
            'Tax'      => '1.30'
        );

        $total = $_SESSION['paymentDetails']['Shipping'] + $_SESSION['paymentDetails']['Tax'] + $_SESSION['paymentDetails']['Subtotal'];

        $amount = array(
            'Currency' => 'USD',
            'Total' => number_format($total,2),
            'Details' => $_SESSION['paymentDetails']
        );
        $_SESSION['amount'] = $amount;

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