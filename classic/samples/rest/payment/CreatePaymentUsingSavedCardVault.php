<?php

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

$intent='sale';                                           //Allowed values: sale, authorize, order.Payment intent. Must be set to sale for immediate payment, authorize to authorize a payment for capture later, or order to create an order.    

$cardID='CARD-8N17066680732224LLRKDUGY';                  // Saved credit card id from vault.

$orderItems = array();
$Item = array(
    'Sku'         => 'MOBEYHZ2YAXZMF2J',                  // Stock keeping unit corresponding (SKU) to item.
    'Name'        => 'Apple iPhone 6 (Space Grey, 16 GB)',// Item name. 127 characters max.    
    'Quantity'    => '1',                                 // Number of a particular item. 10 characters max
    'Price'       => '7.50',                              // Item cost. 10 characters max. 
    'Currency'    => 'USD',                               // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
);
array_push($orderItems, $Item);

$Item = array(
    'Sku'         => 'MOBEYHZ2YAXZMF21',                   // Stock keeping unit corresponding (SKU) to item.
    'Name'        => 'Apple iPhone 7',                     // Item name. 127 characters max.   
    'Quantity'    => '5',                                  // Number of a particular item. 10 characters max
    'Price'       => '2.00',                               // Item cost. 10 characters max.
    'Currency'    => 'USD',                                // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
);
array_push($orderItems, $Item);


$paymentDetails = array(
    'Subtotal' => '17.50',                                  // Amount of the subtotal of the items. **Required** if line items are specified. 10 characters max, with support for 2 decimal places.
    'Shipping' => '1.20',                                   // Amount charged for shipping. 10 characters max with support for 2 decimal places.
    'Tax'      => '1.30',                                   // Amount charged for tax. 10 characters max with support for 2 decimal places.
    'GiftWrap' => ''                                        // Amount being charged as gift wrap fee. 
);

$amount = array(
    'Currency' => 'USD',                                    //Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies. 
    'Total'    => '20.00',                                  //Required. Total amount charged from the payer to the payee. In case of a refund, this is the refunded amount to the original payer from the payee. 10 characters max with support for 2 decimal places.
);

$transaction = array(
    'ReferenceId'    => '',                                 // Optional parameter. Merchant identifier to the purchase unit. Maximum length: 256. 
    'Description'    => '',                                 // Payment description for particular transaction. Maximum length: 127.
    'InvoiceNumber'  => '',                                 // Unique id of the Invoice. Maximum length: 127.
    'Custom'         => '',                                 // free-form field for the use of clients. Maximum length: 127.
    'SoftDescriptor' => '',                                 // Soft descriptor used when charging this funding source. If length exceeds max length, the value will be truncated. Maximum length: 22.
    'NotifyUrl'      => '',                                 // URL to send payment notifications. Maximum length: 2048. Format: uri.
    'OrderUrl'       => ''                                  // Url on merchant site pertaining to this payment. Maximum length: 2048. Format: uri.
);

$requestData = array(
    'intent'         => $intent,
    'orderItems'     => $orderItems,
    'paymentDetails' => $paymentDetails,
    'amount'         => $amount,
    'transaction'    => $transaction
);

$returnArray = $PayPal->CreatePaymentUsingSavedCardVault($requestData,$cardID);
echo "<pre>";
print_r($returnArray);
