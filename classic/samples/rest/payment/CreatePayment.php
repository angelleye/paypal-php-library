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
// This is Create Payment with Creditcard.
$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$intent='sale';                                                 // Allowed values: sale, authorize, order.Payment intent. Must be set to sale for immediate payment, authorize to authorize a payment for capture later, or order to create an order.
$ExperienceProfileId = '';                                      // Optional. PayPal generated identifier for the merchant's payment experience profile. Refer to [this](https://developer.paypal.com/docs/api/#payment-experience) link to create experience profile ID.
$NoteToPayer = 'Contact us for any questions on your order.';   // free-form field for the use of clients to pass in a message to the payer.

$paymentCard = array(
    'Type'              => 'visa',                           // Required.  The card type.Possible values: VISA, AMEX, SOLO, JCB, STAR, DELTA, DISCOVER, SWITCH, MAESTRO, CB_NATIONALE, CONFINOGA, COFIDIS, ELECTRON, CETELEM, CHINA_UNION_PAY, MASTERCARD.
    'Number'            => '4032039334307404',               // Required.  The card number.  No spaces or punctuation.
    'ExpireMonth'       => '12',                             // Required.  The two-digit expiry month for the card.
    'ExpireYear'        => '2021',                           // Required.  The four-digit expiry year for the card.
    'Cvv2'              => '012',                            // Required.  The validation code for the card. Supported for payments but not for saving payment cards for future use.
    'FirstName'         => 'Test',                           // Required.  The first name of the card holder.
    'LastName'          => 'Testerson',                      // The last name of the card holder.
    'BillingCountry'    => 'US',                             // Required. The two-letter country code. For Example 'US'.
    'StartMonth'        => '',                               // The two-digit start month for the card. Required for UK Maestro cards.
    'StartYear'         => '',                               // The four-digit start year for the card. Required for UK Maestro cards. 
    'ExternalCustomerId'=> '',                               // The externally-provided ID of the customer for whom to list credit cards.
    'Status'            => '',                               // Possible values: EXPIRED, ACTIVE. The state of the funding instrument.
    'CardProductClass'  => '',                               // Possible values: CREDIT, DEBIT, GIFT, PAYPAL_PREPAID, PREPAID, UNKNOWN. The product class of the financial instrument issuer.
    'issue_number'      => ''                                // The one- to two-digit card issue number. Required for UK Maestro cards. Maximum length: 2.    
);
// billingAddress object with PaymentCard (Optional).
$billingAddress = array(
    'line1'        => '3909 Witmer Road',                    // Required.  First street address.
    'line2'        => 'Niagara Falls',                       // Optional line 2 of the Address
    'city'         => 'Niagara Falls',                       // Required.  Name of City.    
    'state'        => 'NY',                                  // Required. 2 letter code for US states, and the equivalent for other countries..
    'postal_code'  => '14305',                               // Required. postal code of your area.
    'country_code' => 'US',                                  // 2 letter country code..   
    'phone'        => '716-298-1822'                         // Required.  Postal code of payer.
);

$orderItems = array();
$Item = array(
    'Sku'         => 'MOBEYHZ2YAXZMF2J',                     // Stock keeping unit corresponding (SKU) to item.
    'Name'        => 'Apple iPhone 6 (Space Grey, 16 GB)',   // Item name. 127 characters max.
    'Description' => 'With a beautiful finish and a light weight',  // Description of the item. Only supported when the `payment_method` is set to `paypal`.
    'Quantity'    => '1',                                    // Number of a particular item. 10 characters max
    'Price'       => '7.50',                                 // Item cost. 10 characters max. 
    'Currency'    => 'USD',                                  // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
    'Tax'         => '0.3'                                   // Tax of the item. Only supported when the `payment_method` is set to `paypal`.    
);
array_push($orderItems, $Item);

$Item = array(
    'Sku'         => 'MOBEYHZ2YAXZMF21',                     // Stock keeping unit corresponding (SKU) to item.
    'Name'        => 'Apple iPhone 7',                       // Item name. 127 characters max.
    'Description' => '',                                     // Description of the item. Only supported when the `payment_method` is set to `paypal`.
    'Quantity'    => '5',                                    // Number of a particular item. 10 characters max
    'Price'       => '2.00',                                 // Item cost. 10 characters max.
    'Currency'    => 'USD',                                  // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
    'Tax'         => '0.2'                                   // Tax of the item. Only supported when the `payment_method` is set to `paypal`.    
);
array_push($orderItems, $Item);


$paymentDetails = array(
    'Subtotal' => '17.50',                                   // Amount of the subtotal of the items. **Required** if line items are specified. 10 characters max, with support for 2 decimal places.
    'Shipping' => '1.20',                                    // Amount charged for shipping. 10 characters max with support for 2 decimal places.
    'Tax'      => '1.30',                                    // Amount charged for tax. 10 characters max with support for 2 decimal places.
    'GiftWrap' => ''                                         // Amount being charged as gift wrap fee. 
);

$amount = array(
    'Currency' => 'USD',                                     //Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies. 
    'Total'    => '20.00',                                   //Required. Total amount charged from the payer to the payee. In case of a refund, this is the refunded amount to the original payer from the payee. 10 characters max with support for 2 decimal places.
);

$transaction = array(
    'ReferenceId'    => '',                                  // Optional parameter. Merchant identifier to the purchase unit. Maximum length: 256. 
    'Description'    => '',                                  // Payment description for particular transaction. Maximum length: 127.
    'InvoiceNumber'  => '',                                  // Unique id of the Invoice. Maximum length: 127.
    'Custom'         => '',                                  // free-form field for the use of clients. Maximum length: 127.
    'SoftDescriptor' => '',                                  // Soft descriptor used when charging this funding source. If length exceeds max length, the value will be truncated. Maximum length: 22.
    'NotifyUrl'      => '',                                  // URL to send payment notifications. Maximum length: 2048. Format: uri.
    'OrderUrl'       => ''                                   // Url on merchant site pertaining to this payment. Maximum length: 2048. Format: uri.
);

$requestData = array(
    'intent'         => $intent,
    'paymentCard'    => $paymentCard,
    'billingAddress' => $billingAddress,
    'orderItems'     => $orderItems,
    'paymentDetails' => $paymentDetails,
    'amount'         => $amount,
    'transaction'    => $transaction,
    'ExperienceProfileId' => $ExperienceProfileId,
    'NoteToPayer'    => $NoteToPayer
);

$returnArray = $PayPal->CreatePayment($requestData);
echo "<pre>";
print_r($returnArray);
