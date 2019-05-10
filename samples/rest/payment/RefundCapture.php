<?php
// Refunds a captured payment, by ID. In the JSON request body, include an amount object.

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

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$captureId = '86C113543M927163C';                                                // The ID of the captured payment to refund. AuthorizationCapture.php returns object with CaptureID.

$amount = array(
    'Currency' => 'USD',                                       //Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies. 
    'Total'    => '4.00',                                      //Required. Total amount charged from the payer to the payee. In case of a refund, this is the refunded amount to the original payer from the payee. 10 characters max with support for 2 decimal places.
);

$refundParameters = array(
    'Description'   => 'this is desc for refund',                                     // Description of what is being refunded for. Character length and limitations: 255 single-byte alphanumeric characters.
    'RefundSource'  => '',                                     // Valid Values: ["INSTANT_FUNDING_SOURCE", "ECHECK", "UNRESTRICTED"]. Type of PayPal funding source (balance or eCheck) that can be used for auto refund.
    'Reason'        => '',                                     // Reason description for the Sale transaction being refunded.
    'InvoiceNumber' => '',                                     // The invoice number that is used to track this payment. Character length and limitations: 127 single-byte alphanumeric characters.
    'RefundAdvice'  => ''                                      // Flag to indicate that the buyer was already given store credit for a given transaction.
);

$returnArray = $PayPal->RefundCapture($captureId,$amount,$refundParameters);
echo "<pre>";
print_r($returnArray);
