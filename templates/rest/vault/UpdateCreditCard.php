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
$credit_card_id='';             //Required.The credit_card_id is the ID of the stored credit card. 

$requestData = array(
    array(
        'operation' => '',                                   //Valid Values: ["add", "remove", "replace", test"]
        'path'      => 'type',                              //Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
        'value'     => ''                                    //Value to add/remove/replace/move/copy/test
    ),
    array(
        'operation' => '',                                  //Valid Values: ["add", "remove", "replace", "test"]
        'path'      => 'number',                           // Required.  Credit card number.  No spaces or punctuation.  
        'value'     => ''                                   //Value to add/remove/replace/move/copy/test
    ),    
    array(
        'operation' => '',                                  //Valid Values: ["add", "remove", "replace", "test"]
        'path'      => 'expire_month',                     //Required.  Credit card expiration Month.  Format is MM 
        'value'     => ''                                   //Value to add/remove/replace/move/copy/test
    ), 
    array(
        'operation' => '',                                  //Valid Values: ["add", "remove", "replace", "test"]
        'path'      => 'expire_year',                      // Required.  Credit card expiration year.  Format is YYYY
        'value'     => ''                                   //Value to add/remove/replace/move/copy/test
    ),     
    array(
        'operation' => '',                                  //Valid Values: ["add", "remove", "replace",  "test"]
        'path'      => 'cvv2',                             // Requirements determined by your PayPal account settings.  Security digits for credit card.                                     
        'value'     => ''                                   //Value to add/remove/replace/move/copy/test
    ),         
    array(
        'operation' => '',                                  //Valid Values: ["add", "remove", "replace", "test"]
        'path'      => 'first_name',                       //Required.  Payer's first name.
        'value'     => ''                                   //Value to add/remove/replace/move/copy/test
    ),  
    array(
        'operation' => 'replace',                                  //Valid Values: ["add", "remove", "replace", "test"]
        'path'      => 'last_name',                        // Required.  Payer's last name.
        'value'     => 'Mehta'                                   //Value to add/remove/replace/move/copy/test
    ),  
    array(
        'operation' => '',                                  //Valid Values: ["add", "remove", "replace", "test"]
        'path'      => 'merchant_id',                      //The ID of the merchant for whom to list credit cards.
        'value'     => ''                                   //Value to add/remove/replace/move/copy/test
    ),      
    array(
        'operation' => '',                                  //Valid Values: ["add", "remove", "replace","test"]
        'path'      => 'external_customer_id',             //The externally-provided ID of the customer for whom to list credit cards.
        'value'     => ''                                   //Value to add/remove/replace/move/copy/test
    ),      
    array(
        'operation' => '',                                  //Valid Values: ["add", "remove", "replace","test"]
        'path'      => 'external_card_id',                 //The externally-provided ID of the credit card.
        'value'     => ''                                   //Value to add/remove/replace/move/copy/test
    ),
    array(
        'operation' => '',                                   //Valid Values: ["add", "remove", "replace","test"]
        'path'      => 'billing_address',                   //The externally-provided ID of the credit card.
        'value'     => array(
                            'line1' => '',                   // Required.  First street address.
                            'line2' => '',                   // Optional line 2 of the Address
                            'city'  => '',                    // Required.  Name of City.    
                            'state' => '',                   // Required. 2 letter code for US states, and the equivalent for other countries..
                            'postal_code' => '',             // Required. postal code of your area.
                            'country_code' => '',            // 2 letter country code..   
                            'phone' => '',                   // Required.  Postal code of payer.
                        )                          
    )
);
$returnArray = $PayPal->UpdateCreditCard($requestData,$credit_card_id);
echo "<pre>";
print_r($returnArray);
