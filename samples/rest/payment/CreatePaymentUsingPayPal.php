<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$intent='sale';                                             //Allowed values: sale, authorize, order.Payment intent. Must be set to sale for immediate payment, authorize to authorize a payment for capture later, or order to create an order.    

// ### Notice
// If your intent is "order" set this as URL array.
// ReturnUrl=OrderGet.php?success=true 
// CancelUrl=OrderGet.php?success=false
//
// ### For execute payment
// If your intent is "sale" or "authorize" set this as URL array.
//ReturnUrl=ExecutePayment.php?success=true
//CancelUrl=ExecutePayment.php?success=false


$urls= array(
    'ReturnUrl'   => 'ExecutePayment.php?success=true',                                    // Required when Pay using paypal. Example : ExecutePayment.php?success=true
    'CancelUrl'   => 'ExecutePayment.php?success=false',                                    // Required when Pay using paypal. Example : ExecutePayment.php?success=false
    'BaseUrl'     => 'http://localhost/paypal-php-library/templates/rest/payment/'                                     // Required. 
);

$invoiceNumber='INVC23985684658';
$ExperienceProfileId = '';                                   // Optional. PayPal generated identifier for the merchant's payment experience profile. Refer to [this](https://developer.paypal.com/docs/api/#payment-experience) link to create experience profile ID.
$NoteToPayer = 'This is my Note to Payer';                                        // Optional. free-form field for the use of clients to pass in a message to the payer.

$orderItems = array();
$Item = array(
    'Sku'         => '123123',                                     // Stock keeping unit corresponding (SKU) to item.
    'Name'        => 'Ground Coffee 40 oz',                                     // Item name. 127 characters max.
    'Description' => 'Item1 description',                                     // Description of the item. Only supported when the `payment_method` is set to `paypal`.
    'Quantity'    => '1',                                     // Number of a particular item. 10 characters max
    'Price'       => '7.5',                                     // Item cost. 10 characters max. 
    'Currency'    => 'USD',                                     // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
    'Tax'         => ''                                      // Tax of the item. Only supported when the `payment_method` is set to `paypal`.    
);
array_push($orderItems, $Item);

$Item = array(
    'Sku'         => '321321',                                     // Stock keeping unit corresponding (SKU) to item.
    'Name'        => 'Granola bars',                                     // Item name. 127 characters max.
    'Description' => '',                                     // Description of the item. Only supported when the `payment_method` is set to `paypal`.
    'Quantity'    => '2',                                     // Number of a particular item. 10 characters max
    'Price'       => '5',                                     // Item cost. 10 characters max. 
    'Currency'    => 'USD',                                     // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
    'Tax'         => ''                                      // Tax of the item. Only supported when the `payment_method` is set to `paypal`.    
);
array_push($orderItems, $Item);


$paymentDetails = array(
    'Subtotal' => '17.50',                                        // Amount of the subtotal of the items. **Required** if line items are specified. 10 characters max, with support for 2 decimal places.
    'Shipping' => '1.2',                                        // Amount charged for shipping. 10 characters max with support for 2 decimal places. 
    'Tax'      => '1.3',                                        // Amount charged for tax. 10 characters max with support for 2 decimal places. 
    'GiftWrap' => ''                                         // Amount being charged as gift wrap fee. 
);

$amount = array(
    'Currency' => 'USD',                                       //Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies. 
    'Total'    => '20',                                       //Required. Total amount charged from the payer to the payee. In case of a refund, this is the refunded amount to the original payer from the payee. 10 characters max with support for 2 decimal places. 
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
    'NoteToPayer'    => $NoteToPayer
);

$returnArray = $PayPal->create_payment_with_paypal($requestData);
echo "<h3>Redirect here to execute => {$returnArray['approvalUrl']}</h3>";
echo "<h3>Payment State : ".$returnArray['payment']->state." </h3>";
echo "(Payment ID : This will use for Execute payment in the next step)<h3>Payment ID: ".$returnArray['payment']->id." </h3>";
echo "<pre>";
var_dump($returnArray);
?>