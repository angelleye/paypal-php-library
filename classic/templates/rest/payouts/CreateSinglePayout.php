<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(                
    'Sandbox' => $sandbox,
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);

$PayPal = new \angelleye\PayPal\rest\payouts\PayoutsAPI($configArray);

$batchHeader = array(
    'SenderBatchId' => uniqid(),                                   // A sender-specified ID number. Tracks the batch payout in an accounting system.Note: PayPal prevents duplicate batches from being processed. If you specify a `sender_batch_id` that was used in the last 30 days, the API rejects the request and returns an error message that indicates the duplicate `sender_batch_id` and includes a HATEOAS link to the original batch payout with the same `sender_batch_id`. If you receive a HTTP `5nn` status code, you can safely retry the request with the same `sender_batch_id`. In any case, the API completes a payment only once for a specific `sender_batch_id` that is used within 30 days.
    'EmailSubject'  => '',          // The subject line text for the email that PayPal sends when a payout item completes. The subject line is the same for all recipients. Value is an alphanumeric string with a maximum length of 255 single-byte characters.    
);

$amount = array(
    'currency' => '',                                    // Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies. 
    'value'    => '',                                      // Required. Total amount charged from the payer to the payee. In case of a refund, this is the refunded amount to the original payer from the payee. 10 characters max with support for 2 decimal places. 
);

$PayoutItem = array(
    //  ### RecipientType 
    //  Valid values: EMAIL | PHONE | PAYPAL_ID. 
    //  The type of ID that identifies the payment receiver.
    //  EMAIL. Unencrypted email. Value is a string of up to 127 single-byte characters.
    //  PHONE. Unencrypted phone number.
    //  Note: The PayPal sandbox does not support the PHONE recipient type.
    //  PAYPAL_ID Encrypted PayPal account number.
    
    'RecipientType' => '',                                 // Valid values: EMAIL | PHONE | PAYPAL_ID.      
    'Note'          => '',            // Optional. A sender-specified note for notifications. Value is any string value. Maximum length: 4000.
    'Receiver'      => '',    // The receiver of the payment. Corresponds to the recipient_type value in the request. Maximum length: 127.
    'SenderItemId'  => '',                         // A sender-specified ID number. Tracks the batch payout in an accounting system. Maximum length: 30.    
);

$requestData=array(    
    "batchHeader" => $batchHeader,
    "amount"      => $amount,
    "PayoutItem"  => $PayoutItem
);

$returnArray = $PayPal->CreateSinglePayout($requestData);
echo "<pre>";
print_r($returnArray);
