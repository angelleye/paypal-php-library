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

$capture_id = '6X1812201H9506636';       // The PayPal-generated ID for the captured payment to refund.

$amount = array(
    'currency_code' => 'USD',
    'value' => 7.50,                    // The amount to refund.
);

$invoice_id = 'AEINV-323';              // Maximum length: 127. The API caller-provided external invoice number for this order. Appears in both the payer's transaction history and the emails that the payer receives.
$note_to_payer = 'Defective product';   // Maximum length: 255. The reason for the refund. Appears in both the payer's transaction history and the emails that the payer receives.

$requestArray = array(

    'amount' => $amount,
    'note_to_payer' => $note_to_payer,
    'invoice_id' => $invoice_id,
);

$response = $PayPal->RefundCapturedPayment($capture_id,$requestArray);

echo "<pre>";
print_r($response);
exit;