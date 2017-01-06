<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$agreement_id  = '';                                // Required. Identifier of the agreement resource to update.

$agreement = array(
    "description" => '',                            // Description of the agreement.   
    "shipping_address" => array(
        "line1" => '',                              // Line 1 of the Address (eg. number, street, etc).
        "line2" => '',                              // Optional line 2 of the Address (eg. suite, apt #, etc.). 
        "city"  => '',                              // City name.
        "country_code" => '',                       // 2 letter country code.
        "postal_code" => '',                        // Zip code or equivalent is usually required for countries that have them. For list of countries that do not have postal codes please refer to http://en.wikipedia.org/wiki/Postal_code.
        "state" => '',                              // 2 letter code for US states, and the equivalent for other countries.        
    )
);



$returnArray = $PayPal->update_billing_agreement($agreement_id,$agreement);
echo "<pre>";
var_dump($returnArray);
?>