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

$paymentId = $_SESSION['payment_id'];
$payer_id = $_SESSION['payer_info']['payer_id'];
$amount = $_SESSION['amount'];
$returnArray = $PayPal->ExecutePayment($paymentId,$payer_id,$amount);

if($returnArray['RESULT'] == 'Success'){
    $_SESSION['Payment_transaction_id'] = $returnArray['PAYMENT']['transactions'][0]['related_resources'][0]['sale']['id'];
    $_SESSION['RESULT'] = $returnArray;
    header('Location: order-complete.php');
}
else{
    /**
     * Error page redirection
     */
    $_SESSION['rest_errors'] = true;
    $_SESSION['errors'] = $returnArray;
    header('Location: ../../error.php');
}