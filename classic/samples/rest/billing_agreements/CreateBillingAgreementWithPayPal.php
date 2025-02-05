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

$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$planId = 'P-4EM72741GL399293LUM5YCII';                     // Required. Billing plan id that will be used to create a billing agreement.

$agreement = array(
    "State" => '',                                          // State of the agreement.
    "Name" => 'DPRP',                                       // Required. Name of the agreement.
    "Description" => 'Payment with Paypal',                 // Required. Description of the agreement.
    "StartDate" => '2019-06-17T9:45:04Z'                    // Required. Start date of the agreement. Date format yyyy-MM-dd z, as defined in [ISO8601](http://tools.ietf.org/html/rfc3339#section-5.6).
);

// Payerinfo is Required.
$payer =array(
    "PaymentMethod" => 'paypal',                         // Required. Valid Values: ["credit_card", "bank", "paypal", "pay_upon_invoice", "carrier", "alternate_payment"]. Payment method being used - PayPal Wallet payment, Bank Direct Debit  or Direct Credit card.
    "AccountType" => ''                                  // Valid Values: ["BUSINESS", "PERSONAL", "PREMIER"]. Type of account relationship payer has with PayPal. 
);

$shippingAddress = array(
    "Line1" => '111 First Street',                              // Line 1 of the Address (eg. number, street, etc).
    "Line2" => '',                                              // Optional line 2 of the Address (eg. suite, apt #, etc.). 
    "City"  => 'Saratoga',                                      // City name.
    "CountryCode" => 'US',                                      // 2 letter country code.
    "PostalCode" => '95070',                                    // Zip code or equivalent is usually required for countries that have them. For list of countries that do not have postal codes please refer to http://en.wikipedia.org/wiki/Postal_code.
    "State" => 'CA',                                            // 2 letter code for US states, and the equivalent for other countries.
    "NormalizationStatus" => ''                                 // Valid Values: ["UNKNOWN", "UNNORMALIZED_USER_PREFERRED", "NORMALIZED", "UNNORMALIZED"]. Address normalization status    
);

$requestData = array(
    "planId"          => $planId,
    "agreement"       => $agreement,
    "payer"           => $payer,
    "shippingAddress" => $shippingAddress
);

$returnArray = $PayPal->CreateBillingAgreement($requestData);
echo "<pre>";
print_r($returnArray);
