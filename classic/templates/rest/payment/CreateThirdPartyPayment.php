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

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$intent='';                                             //Allowed values: sale, authorize, order.Payment intent. Must be set to sale for immediate payment, authorize to authorize a payment for capture later, or order to create an order.    

// ### Notice
// If your intent is "order" set this as URL array.
// ReturnUrl=OrderGet.php?success=true 
// CancelUrl=OrderGet.php?success=false
//
// ### For execute payment
// If your intent is "sale" or "authorize" set this as URL array.
//ReturnUrl=ExecutePayment.php?success=true
//CancelUrl=ExecutePayment.php?success=false

$third_party_merchant_email = '';                           // Specify a payee with that user's email or merchant id Merchant Id can be found at https://www.paypal.com/businessprofile/settings/

$urls= array(
    'ReturnUrl'   => '',                                    // Required when Pay using paypal. Example : ExecutePayment.php?success=true
    'CancelUrl'   => '',                                    // Required when Pay using paypal. Example : ExecutePayment.php?success=false
    'BaseUrl'     => ''                                     // Required. 
);

$invoiceNumber='';
$ExperienceProfileId = '';                                   // Optional. PayPal generated identifier for the merchant's payment experience profile. Refer to [this](https://developer.paypal.com/docs/api/#payment-experience) link to create experience profile ID.
$NoteToPayer = '';                                           // Optional. free-form field for the use of clients to pass in a message to the payer.

$orderItems = array();
$Item = array(
    'Sku'         => '',                                     // Stock keeping unit corresponding (SKU) to item.
    'Name'        => '',                                     // Item name. 127 characters max.
    'Description' => '',                                     // Description of the item. Only supported when the `payment_method` is set to `paypal`.
    'Quantity'    => '',                                     // Number of a particular item. 10 characters max
    'Price'       => '',                                     // Item cost. 10 characters max. 
    'Currency'    => '',                                     // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
    'Tax'         => ''                                      // Tax of the item. Only supported when the `payment_method` is set to `paypal`.    
);
array_push($orderItems, $Item);

$Item = array(
    'Sku'         => '',                                    // Stock keeping unit corresponding (SKU) to item.
    'Name'        => '',                                    // Item name. 127 characters max.
    'Description' => '',                                    // Description of the item. Only supported when the `payment_method` is set to `paypal`.
    'Quantity'    => '',                                    // Number of a particular item. 10 characters max
    'Price'       => '',                                    // Item cost. 10 characters max. 
    'Currency'    => '',                                    // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
    'Tax'         => ''                                     // Tax of the item. Only supported when the `payment_method` is set to `paypal`.    
);
array_push($orderItems, $Item);


$paymentDetails = array(
    'Subtotal' => '',                                       // Amount of the subtotal of the items. **Required** if line items are specified. 10 characters max, with support for 2 decimal places.
    'Shipping' => '',                                       // Amount charged for shipping. 10 characters max with support for 2 decimal places. 
    'Tax'      => '',                                       // Amount charged for tax. 10 characters max with support for 2 decimal places. 
    'GiftWrap' => ''                                        // Amount being charged as gift wrap fee. 
);

$amount = array(
    'Currency' => '',                                       //Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies. 
    'Total'    => '',                                       //Required. Total amount charged from the payer to the payee. In case of a refund, this is the refunded amount to the original payer from the payee. 10 characters max with support for 2 decimal places. 
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
    'invoiceNumber' => $invoiceNumber,
    'orderItems'     => $orderItems,
    'paymentDetails' => $paymentDetails,
    'amount'         => $amount,
    'transaction'    => $transaction,
    'urls'           => $urls,
    'ExperienceProfileId' => $ExperienceProfileId,
    'NoteToPayer'    => $NoteToPayer,
    'Payee'          => $third_party_merchant_email
);

$returnArray = $PayPal->CreateThirdPartyPayment($requestData);
print_r($returnArray);
