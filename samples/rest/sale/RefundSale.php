<?php

/**
 * A sale is a completed payment. 
 * Shows details for a sale, by ID. Returns only sales that were created through the REST API.
 */

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

$sale_id = '9SS01680HD336413P';         // The ID of the sale transaction to refund.

$amount = array(
    'Currency' => 'USD',                //Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies. 
    'Total'    => '4.00',               //Required. The total amount charged to the payee by the payer. For refunds, represents the amount that the payee refunds to the original payer. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point. 3) Two digits after the decimal point.
    'Details'  => array(
        'Subtotal' => '',               // The subtotal amount for the items. If the request includes line items, this property is required. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point. 3) Two digits after the decimal point. 
        'Shipping' => '',               // The shipping fee. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point. 3) Two digits after the decimal point.
        'Tax'      => '',               // The tax. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point. 3) Two digits after the decimal point.
        'HandlingFee' => '',            // The handling fee. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point.  3) Two digits after the decimal point. Supported for the PayPal payment method only.
        'ShippingDiscount' => '',       // The shipping fee discount. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point.  3) Two digits after the decimal point. Supported for the PayPal payment method only.
        'Insurance' => '',              // The insurance fee. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point.  3) Two digits after the decimal point. Supported for the PayPal payment method only.
        'GiftWrap' => ''                // The gift wrap fee. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point.  3) Two digits after the decimal point.   
    )
);

$refundParameters = array(
    'Description'   => 'Refund for double charge.',            // The refund description. Value is a string of single-byte alphanumeric characters. Maximum length: 255.
    'Reason'        => '',                                     // The refund reason description. Maximum length: 30.
    'InvoiceNumber' => '',                                     // The invoice number that tracks this payment. Value is a string of single-byte alphanumeric characters. Maximum length: 127.
);

$returnArray = $PayPal->RefundSale($sale_id,$amount,$refundParameters);
echo "<pre>";
print_r($returnArray);