<?php
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
$PayPal = new \angelleye\PayPal\rest\invoice\InvoiceAPI($configArray);

$record = array(
    'Method' => 'CASH',                                    // Valid Values: ["BANK_TRANSFER", "CASH", "CHECK", "CREDIT_CARD", "DEBIT_CARD", "PAYPAL", "WIRE_TRANSFER", "OTHER"].  The payment mode or method. Required with the `EXTERNAL` payment type.
    'Note'   => 'Cash received.',                          // Optional. A note associated with the payment.
    'Date'   => '2019-01-06 03:30:00 PST',                 // The date when the invoice was paid. The date format is *yyyy*-*MM*-*dd* *z* as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).
);

$amount = array(
    'currency' => 'USD',                                   // The three-letter ISO 4217 alphabetic currency code. 
    'value'    => '19.51'                                  // The amount up to N digits after the decimal separator, as defined in ISO 4217 for the appropriate currency code. 
);

$invoiceId = 'INV2-BDWB-GSEF-KKVN-UNHF';                   // Required. The ID of the invoice to mark as paid.

$returnArray = $PayPal->RecordPayment($invoiceId,$record,$amount);
echo "<pre>";
print_r($returnArray);
