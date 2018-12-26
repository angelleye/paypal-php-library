<?php

/* Include required library files. */

require_once('../../vendor/autoload.php');
require_once('../../includes/config.php');

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

    // Please run the example with $details object in  samples\rest\payment\ExecutePayment.php in our library

//    $details = array(
//        'Shipping' => '2.2',
//        'Tax' => '1.3',
//        'HandlingFee' => '',
//        'ShippingDiscount' => '',
//        'Insurance' => '',
//        'GiftWrap' => '',
//        'Fee' => '',
//        'Subtotal' => '17.50'
//    );
    $amount = array();
//
//    $amount = array(
//        'Currency' => 'USD',
//        'Total' => '21',
//        'Details' => $details
//    );

    $returnArray = $PayPal->execute_payment($paymentId,$payer_id,$amount);

    if($returnArray['RESULT'] == 'Success'){
        $_SESSION['RESULT'] = $returnArray;
        header('Location: order-complete.php');
    }
    else{
        /**
         * Error page redirection
         */
        $_SESSION['rest_errors'] = true;
        $_SESSION['errors'] = $returnArray;
        header('Location: ../error.php');
    }
} else {
    echo "User Cancelled the Approval";
    exit;
}
