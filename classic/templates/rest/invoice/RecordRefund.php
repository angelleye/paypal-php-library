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

$invoiceId = '';        // Required. Inovoice id for which to record refunds.

$refundDetail = array(
    'Type' => '',                         // Valid Values: ["PAYPAL", "EXTERNAL"] The PayPal refund type. Indicates whether refund was paid in invoicing flow through PayPal or externally. In the case of mark-as-refunded API, the supported refund type is `EXTERNAL`. For backward compatability, the `PAYPAL` refund type is still supported.
    'TransactionId' => '',                // The PayPal refund transaction ID. Required with the `PAYPAL` refund type.
    'Date'   => '',                       // Date on which the invoice was refunded. Date format: yyyy-MM-dd z. For example, 2014-02-27 PST.
    'Note'   => '',                       // Optional note associated with the refund.    
);

//Amount to be recorded as refund against invoice. If this field is not passed, the total invoice paid amount is recorded as refund.
$amount = array(
    'currency' => '',                                      // The three-letter ISO 4217 alphabetic currency code. 
    'value'    => ''                                       // The amount up to N digits after the decimal separator, as defined in ISO 4217 for the appropriate currency code. 
);

$requestData = array(
    'invoiceId' => $invoiceId,
    'refundDetail' => $refundDetail,
    'amount' => $amount
);
$returnArray = $PayPal->RecordRefund($requestData);
echo "<pre>";
print_r($returnArray);
