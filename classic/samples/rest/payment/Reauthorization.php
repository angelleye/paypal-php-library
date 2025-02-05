<?php
// ##Reauthorization
/**
 * Re-authorizes a PayPal account payment, by authorization ID. 
 * To ensure that funds are still available, re-authorize a payment after the initial three-day honor period. 
 * Supports only the amount request parameter. You can re-authorize a payment only once from four to 29 days after 
 * three-day honor period for the original authorization expires. 
 * If 30 days have passed from the original authorization, you must create a new authorization instead.
 * A re-authorized payment itself has a new three-day honor period. You can re-authorize a transaction once for 
 * up to 115% of the originally authorized amount, not to exceed an increase of $75 USD.
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

$authorizationId = "O-3E566053FX793851D";      // The ID of the authorization to re-authorize.

$amount = array(
    'Currency' => 'USD',   //Required. 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/). PayPal does not support all currencies. 
    'Total'    => '4.00',  //Required. The total amount charged to the payee by the payer. For refunds, represents the amount that the payee refunds to the original payer. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point. 3) Two digits after the decimal point.
    'Details'  => array(
        'Subtotal' => '',   // The subtotal amount for the items. If the request includes line items, this property is required. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point. 3) Two digits after the decimal point. 
        'Shipping' => '',   // The shipping fee. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point. 3) Two digits after the decimal point.
        'Tax'      => '',    // The tax. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point. 3) Two digits after the decimal point.
        'HandlingFee' => '',   // The handling fee. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point.  3) Two digits after the decimal point. Supported for the PayPal payment method only.
        'ShippingDiscount' => '',  // The shipping fee discount. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point.  3) Two digits after the decimal point. Supported for the PayPal payment method only.
        'Insurance' => '',      // The insurance fee. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point.  3) Two digits after the decimal point. Supported for the PayPal payment method only.
        'GiftWrap' => ''       // The gift wrap fee. Maximum length is 10 characters, which includes: 1) Seven digits before the decimal point. 2) The decimal point.  3) Two digits after the decimal point.   
    )
);


$returnArray = $PayPal->Reauthorization($authorizationId,$amount);
echo "<pre>";
print_r($returnArray);
