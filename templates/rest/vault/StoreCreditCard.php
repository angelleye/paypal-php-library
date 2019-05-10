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
    'Type' => '',                   // Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
    'Number' => '',                 // Required.  Credit card number.  No spaces or punctuation.  
    'ExpireMonth' => '',            // Required.  Credit card expiration Month.  Format is MM
    'ExpireYear' => '',             // Required.  Credit card expiration year.  Format is YYYY
    'Cvv2' => ''                    // Requirements determined by your PayPal account settings.  Security digits for credit card.                                     
);
$payerInfo = array(
    'FirstName' => '',              // Required.  Payer's first name.
    'LastName' => ''                // Required.  Payer's last name.
);

$billingAddress = array(
    'line1' => '',                   // Required.  First street address.
    'line2' => '',                   // Optional line 2 of the Address
    'city' => '',                    // Required.  Name of City.    
    'state' => '',                   // Required. 2 letter code for US states, and the equivalent for other countries..
    'postal_code' => '',             // Required. postal code of your area.
    'country_code' => '',            // 2 letter country code..   
    'phone' => '',                   // Required.  Postal code of payer.
);

$optionalArray = array(
    'MerchantId'=>'',                //The ID of the merchant for whom to list credit cards.
    'ExternalCustomerId'=>'',        //The externally-provided ID of the customer for whom to list credit cards.
    'ExternalCardId'=>''             //The externally-provided ID of the credit card.
);

$requestData = array(
    'creditCard' => $creditCard,
    'payerInfo' => $payerInfo,
    'billingAddress' => $billingAddress,
    'optionalArray' =>$optionalArray
);

$returnArray = $PayPal->StoreCreditCard($requestData);
echo "<pre>";
print_r($returnArray);
