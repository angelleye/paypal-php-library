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

$agreement_id  = 'I-4U6DTW95XNLS';                                  // Required. Identifier of the agreement resource to update.

$agreement = array(
    "description" => 'Payment with Paypal',                 // Description of the agreement.   
    "shipping_address" => array(
        "line1" => '111 First Street',                              // Line 1 of the Address (eg. number, street, etc).
        "line2" => '112 Second Street',                                              // Optional line 2 of the Address (eg. suite, apt #, etc.). 
        "city"  => 'Saratoga',                                      // City name.
        "country_code" => 'US',                                      // 2 letter country code.
        "postal_code" => '95070',                                    // Zip code or equivalent is usually required for countries that have them. For list of countries that do not have postal codes please refer to http://en.wikipedia.org/wiki/Postal_code.
        "state" => 'CA',                                            // 2 letter code for US states, and the equivalent for other countries.        
    )
);

$returnArray = $PayPal->UpdateBillingAgreement($agreement_id,$agreement);
echo "<pre>";
print_r($returnArray);
