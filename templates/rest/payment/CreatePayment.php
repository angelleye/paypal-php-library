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

$intent='';                                                  //Allowed values: sale, authorize, order.Payment intent. Must be set to sale for immediate payment, authorize to authorize a payment for capture later, or order to create an order.    

$paymentCard = array(
    'Type'              => '',                               // Required.  The card type.Possible values: VISA, AMEX, SOLO, JCB, STAR, DELTA, DISCOVER, SWITCH, MAESTRO, CB_NATIONALE, CONFINOGA, COFIDIS, ELECTRON, CETELEM, CHINA_UNION_PAY, MASTERCARD.
    'Number'            => '',                               // Required.  The card number.  No spaces or punctuation.
    'ExpireMonth'       => '',                               // Required.  The two-digit expiry month for the card.
    'ExpireYear'        => '',                               // Required.  The four-digit expiry year for the card.
    'Cvv2'              => '',                               // Required.  The validation code for the card. Supported for payments but not for saving payment cards for future use.
    'FirstName'         => '',                               // Required.  The first name of the card holder.
    'LastName'          => '',                               // The last name of the card holder.
    'BillingCountry'    => '',                               // Required. The two-letter country code. For Example 'US'.
    'StartMonth'        => '',                               // The two-digit start month for the card. Required for UK Maestro cards.
    'StartYear'         => '',                               // The four-digit start year for the card. Required for UK Maestro cards. 
    'ExternalCustomerId'=> '',                               // The externally-provided ID of the customer for whom to list credit cards.
    'Status'            => '',                               // Possible values: EXPIRED, ACTIVE. The state of the funding instrument.
    'CardProductClass'  => '',                               // Possible values: CREDIT, DEBIT, GIFT, PAYPAL_PREPAID, PREPAID, UNKNOWN. The product class of the financial instrument issuer.
    'issue_number'      => ''                                // The one- to two-digit card issue number. Required for UK Maestro cards. Maximum length: 2.    
);
// billingAddress object with PaymentCard (Optional).
$billingAddress = array(
    'line1'        => '',                                    // Required.  First street address.
    'line2'        => '',                                    // Optional line 2 of the Address
    'city'         => '',                                    // Required.  Name of City.    
    'state'        => '',                                    // Required. 2 letter code for US states, and the equivalent for other countries..
    'postal_code'  => '',                                    // Required. postal code of your area.
    'country_code' => '',                                    // 2 letter country code..   
    'phone'        => ''                                     // Required.  Postal code of payer.
);

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
    'Sku'         => '',                                     // Stock keeping unit corresponding (SKU) to item.
    'Name'        => '',                                     // Item name. 127 characters max.
    'Description' => '',                                     // Description of the item. Only supported when the `payment_method` is set to `paypal`.
    'Quantity'    => '',                                     // Number of a particular item. 10 characters max
    'Price'       => '',                                     // Item cost. 10 characters max. 
    'Currency'    => '',                                     // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
    'Tax'         => ''                                      // Tax of the item. Only supported when the `payment_method` is set to `paypal`.    
);
array_push($orderItems, $Item);


$paymentDetails = array(
    'Subtotal' => '',                                        // Amount of the subtotal of the items. **Required** if line items are specified. 10 characters max, with support for 2 decimal places.
    'Shipping' => '',                                        // Amount charged for shipping. 10 characters max with support for 2 decimal places. 
    'Tax'      => '',                                        // Amount charged for tax. 10 characters max with support for 2 decimal places. 
    'GiftWrap' => ''                                         // Amount being charged as gift wrap fee. 
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
    'paymentCard'    => $paymentCard,
    'billingAddress' => $billingAddress,
    'orderItems'     => $orderItems,
    'paymentDetails' => $paymentDetails,
    'amount'         => $amount,
    'transaction'    => $transaction
);

$returnArray = $PayPal->CreatePayment($requestData);
echo "<pre>";
print_r($returnArray);
