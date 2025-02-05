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

$authorization_id  = '3HT90723074597802';       // The PayPal-generated ID for the authorized payment to capture.

/**
 *   To capture a portion of the full authorized amount, specify an amount.
 *   If amount is not specified, the full authorized amount is captured.
 *   The amount must be a positive number and in the same currency as the
 *   authorization against which the payment is being captured.
 */
$amount = array(
    'currency_code' => 'USD',
    'value' => 17.50,              // The amount to capture.
);

$final_capture = true;            // Default : false , Indicates whether you can make additional captures against the authorized payment. Set to true if you do not intend to capture additional payments against the authorization. Set to false if you intend to capture additional payments against the authorization.

$invoice_id  = 'AEINV-663';              // The API caller-provided external invoice number for this order. Appears in both the payer's transaction history and the emails that the payer receives.

/**
 * Any additional payment instructions for PayPal for Partner customers.
 * Enables features for partners and marketplaces, such as delayed disbursement and collection of a platform fee.
 * Applies during order creation for captured payments or during capture of authorized payments.
$payment_instruction  = array(
    'disbursement_mode' => '',
    'platform_fees' => array(
        'amount' => array(
            'currency_code' => '',
            'value' => ''
        ),
        'payee' => array(
            'email_address' => '',
            'merchant_id' => ''
        )
    )
);
 */

$requestArray = array(

    'amount' => $amount,
    'final_capture' => $final_capture,
    'invoice_id' => $invoice_id,
    //'payment_instruction' => $payment_instruction
);


$response = $PayPal->CaptureAuthorizedPayment($authorization_id,$requestArray);

echo "<pre>";
print_r($response);
exit;