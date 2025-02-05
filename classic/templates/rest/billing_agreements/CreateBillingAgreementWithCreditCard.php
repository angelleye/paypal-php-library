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

$planId = '';                             // Required. Billing plan id that will be used to create a billing agreement.

$agreement = array(
    "State" => '',                        // State of the agreement.
    "Name" => '',                         // Required. Name of the agreement.
    "Description" => '',                  // Required. Description of the agreement.
    "StartDate" => ''                     // Required. Start date of the agreement. Date format yyyy-MM-dd z, as defined in [ISO8601](http://tools.ietf.org/html/rfc3339#section-5.6).
);

$creditCard = array(
    'Type' => '',                                           // Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
    'Number' => '',                                         // Required.  Credit card number.  No spaces or punctuation.  
    'ExpireMonth' => '',                                    // Required.  Credit card expiration Month.  Format is MM
    'ExpireYear' => '',                                     // Required.  Credit card expiration year.  Format is YYYY
    'Cvv2' => '',                                           // Requirements determined by your PayPal account settings.  Security digits for credit card.                                     
    'FirstName' => '',                                      // Cardholder's first name.
    'LastName' => '',                                       // Cardholder's last name.  
    'ExternalCustomerId' => '',                             // A unique identifier of the customer to whom this bank account belongs. Generated and provided by the facilitator. **This is now used in favor of `payer_id` when creating or using a stored funding instrument in the vault.
    'MerchantId' => '',                                     // A user provided, optional convenvience field that functions as a unique identifier for the merchant on behalf of whom this credit card is being stored for. Note that this has no relation to PayPal merchant id  
    'PayerId' => '',                                        // A unique identifier that you can assign and track when storing a credit card or using a stored credit card. This ID can help to avoid unintentional use or misuse of credit cards. This ID can be any value you would like to associate with the saved card, such as a UUID, username, or email address. Required when using a stored credit card if a payer_id was originally provided when storing the credit card in vault.
    'ExternalCardId' => '',                                 // A unique identifier of the bank account resource. Generated and provided by the facilitator so it can be used to restrict the usage of the bank account to the specific merchant.        
);

$payer =array(
    "PaymentMethod" => 'credit_card',                         // Valid Values: ["credit_card", "bank", "paypal", "pay_upon_invoice", "carrier", "alternate_payment"]. Payment method being used - PayPal Wallet payment, Bank Direct Debit  or Direct Credit card.    
    "AccountType" => ''                                       // Valid Values: ["BUSINESS", "PERSONAL", "PREMIER"]. Type of account relationship payer has with PayPal. 
);
// Payerinfo is Required. 
$payerInfo = array(
    'email' => '',                                           // Email address representing the payer. 127 characters max.    
    "first_name" => '',                                      // First name of the payer. 
    "last_name" => '',                                       // Last name of the payer.
);

$shippingAddress = array(
    "Line1" => '',                                          // Line 1 of the Address (eg. number, street, etc).
    "Line2" => '',                                          // Optional line 2 of the Address (eg. suite, apt #, etc.). 
    "City"  => '',                                          // City name.
    "CountryCode" => '',                                    // 2 letter country code.
    "PostalCode" => '',                                     // Zip code or equivalent is usually required for countries that have them. For list of countries that do not have postal codes please refer to http://en.wikipedia.org/wiki/Postal_code.
    "State" => '',                                          // 2 letter code for US states, and the equivalent for other countries.
    "NormalizationStatus" => ''                             // Valid Values: ["UNKNOWN", "UNNORMALIZED_USER_PREFERRED", "NORMALIZED", "UNNORMALIZED"]. Address normalization status    
);


$requestData = array(
        "planId"          => $planId,
        "agreement"       => $agreement,
        "creditCard"      => $creditCard,
        "payer"           => $payer,
        "payerInfo"       => $payerInfo ,
        "shippingAddress" => $shippingAddress        
);

$returnArray = $PayPal->CreateBillingAgreementWithCreditCard($requestData);
echo "<pre>";
print_r($returnArray);
