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

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;



/**
 * Setup configuration for the PayPal library.
 * Then load the api context in into $_api_context
 */

$_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($rest_client_id,$rest_client_secret)
            );

/* ### Approval Status */
/* Determine if the user approved the payment or not */
if (isset($_GET['success']) && $_GET['success'] == 'true') {

    /**
     *  Get the payment Object by passing paymentId.
     *  The paymentId is added to the request query parameters when the user is redirected from paypal back to your site.
     */

    $paymentId = $_GET['paymentId'];
    $payment = Payment::get($paymentId, $_api_context);

    /**
     *  ### Payment Execute
     *  PaymentExecution object includes information necessary to execute a PayPal account payment.
     *  The payer_id is added to the request query parameters when the user is redirected from paypal back to your site.
     */

    $execution = new PaymentExecution();
    $execution->setPayerId($_GET['PayerID']);

    /**
     *  ### Optional Changes to Amount
     *  If you wish to update the amount that you wish to charge the customer,
     *  based on the shipping address or any other reason, you could
     *  do that by passing the transaction object with just `amount` field in it.
     *  Here is the example on how we changed the shipping to $1 more than before.
     */

    /*$transaction = new Transaction();
    $amount = new Amount();
    $details = new Details();

    $details->setShipping(2.2)
        ->setTax(1.3)
        ->setSubtotal(17.50);

    $amount->setCurrency('USD');
    $amount->setTotal(21);
    $amount->setDetails($details);
    $transaction->setAmount($amount);

    // Add the above transaction object inside our Execution object.
    $execution->addTransaction($transaction);*/

    try {

        /**
         * Here we are making the call to the execute function in the library,
         * it will Executes, or completes, a PayPal payment that the payer has approved.
         */
        $result = $payment->execute($execution, $_api_context);

        /**
         * Store the result array in to the session for the further display on order-complete.php page.
         * and redirect to that page.
         */
        $_SESSION['RESULT'] = $result->toArray();
        header('Location: order-complete.php');

    } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
        print_r($ex->getData());
        exit;
    }
} else {
    echo "User Cancelled the Approval";
    exit;
}
