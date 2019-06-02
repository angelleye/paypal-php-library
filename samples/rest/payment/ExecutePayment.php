<?php
// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

// #Execute Payment Sample
// This is the second step required to complete
// PayPal checkout. Once user completes the payment, paypal
// redirects the browser to "redirectUrl" provided in the request.
// This sample will show you how to execute the payment
// that has been approved by
// the buyer by logging into paypal site.
// You can optionally update transaction
// information by passing in one or more transactions.
// API used: POST '/v1/payments/payment/<payment-id>/execute'.

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

    // ### Optional Changes to Amount
    // If you wish to update the amount that you wish to charge the customer,
    // based on the shipping address or any other reason, you could
    // do that by passing the transaction object with just `amount` field in it.

    $details = array(
        'Shipping' => '2.20',
        'Tax' => '1.30',
        'HandlingFee' => '',
        'ShippingDiscount' => '',
        'Insurance' => '',
        'GiftWrap' => '',
        'Fee' => '',
        'Subtotal' => '17.50'
    );


    $amount = array(
        'Currency' => 'USD',
        'Total' => '21.00',
        'Details' => $details
    );

    $result = $PayPal->ExecutePayment($paymentId,$payer_id,$amount);
    echo "<pre>";
    print_r($result);
} else {
    echo "User Cancelled the Approval";
    exit;
}
