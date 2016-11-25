<?php

require_once('../autoload.php');
require_once('../src/angelleye/PayPal/RestAPI.php');


$PayPal = new RestAPI();

$creditCard = array(
    'creditCardType' => 'visa',                 // Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
    'creditCardNumber' => '4417119669820331',  // Required.  Credit card number.  No spaces or punctuation.  
    'expireMonth' => '11',                    // Required.  Credit card expiration Month.  Format is MM
    'expireYear' => '2019',                  // Required.  Credit card expiration year.  Format is YYYY
    'cvv' => '012',                         // Requirements determined by your PayPal account settings.  Security digits for credit card.                                     
);
$payerInfo = array(
    'firstName' => 'Joe',           // Required.  Payer's first name.
    'lastName' => 'Shopper'       // Required.  Payer's last name.
);

$BillingAddress = array(
    'line1' => '52 N Main ST',                   // Required.  First street address.
    'line2' => 'Line 2',                              // Optional line 2 of the Address
    'city' => 'Johnstown',                     // Required.  Name of City.    
    'state' => 'OH',                          // Required. 2 letter code for US states, and the equivalent for other countries..
    'postal_code' => '43210',                // Required. postal code of your area.
    'country_code' => 'US',                 // 2 letter country code..   
    'phone' => '408-334-8890',             // Required.  Postal code of payer.
);

$requestData = array(
    "creditCard" => $creditCard,
    "payerInfo" => $payerInfo,
    "BillingAddress" => $BillingAddress
);

$returnArray = $PayPal->StoreCreditCard($requestData);

?>