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

$PayPal = new \angelleye\PayPal\rest\vault\CreditCardAPI($configArray);

$creditCard = array(
    'Type' => 'visa',                           // Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
    'Number' => '4111111111111111',             // Required.  Credit card number.  No spaces or punctuation.  
    'ExpireMonth' => '11',                      // Required.  Credit card expiration Month.  Format is MM
    'ExpireYear' => '2019',                     // Required.  Credit card expiration year.  Format is YYYY
    'Cvv2' => '012',                            // Requirements determined by your PayPal account settings.  Security digits for credit card.                                     
);

$payerInfo = array(
    'FirstName' => 'Joe',                       // Required.  Payer's first name.
    'LastName' => 'Shopper'                     // Required.  Payer's last name.
);

$billingAddress = array(
    'line1' => '52 N Main ST',                  // Required.  First street address.
    'line2' => '',                              // Optional line 2 of the Address
    'city' => 'Johnstown',                      // Required.  Name of City.    
    'state' => 'OH',                            // Required. 2 letter code for US states, and the equivalent for other countries..
    'postal_code' => '43210',                   // Required. postal code of your area.
    'country_code' => 'US',                     // 2 letter country code..   
    'phone' => '408-334-8890',                  // Required.  Postal code of payer.
);

$optionalArray = array(
    'MerchantId'=>'',                           //The ID of the merchant for whom to list credit cards.
    'ExternalCustomerId'=>'',                   //The externally-provided ID of the customer for whom to list credit cards.
    'ExternalCardId'=>''                        //The externally-provided ID of the credit card.
);

$requestData = array(
    'creditCard' => $creditCard,
    'payerInfo' => $payerInfo,
    'billingAddress' => $billingAddress,
    'optionalArray' =>$optionalArray
);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->StoreCreditCard($requestData);

// Write the contents of the response array to the screen for demo purposes.
echo "<pre>";
print_r($PayPalResult);
