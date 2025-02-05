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

$requestData=array(
                'sort_by' => 'create_time',             //Default is create_time. Sorts the vaulted credit cards in the response by either create_time or update_time.      
                'sort_order' => 'desc',                 //Default: desc.Value is asc for ascending order or desc for descending order.
                'merchant_id' => '',                    // Filtering by MerchantId set during CreateCreditCard.
                'page_size'  => '',                     //Default: 10. The number of vaulted credit cards to return in the response beginning with the specified   
                'page'=>'',                             //Default: 1. A zero-relative index of the list of vaulted credit cards.
                'start_time' =>'',                      //For example, 2016-11-05T13:15:30Z. Filters the vaulted credit cards in the response to those created on.  
                'end_time' => ''  ,                     //Filters the vaulted credit cards in the response to those created on or after the start_time date and time and on or before this value 
                'external_card_id'=>'',                 //Filters the vaulted credit cards in the response to those associated with this bank account ID, which is the facilitator-provided ID of the bank account
                'external_customer_id'=>'',             //Filters the vaulted credit cards in the response to those associated with this externally-provided customer ID.
                'total_required'=>''                    //Default true.Indicates whether the response returns the total_items and total_pages values.
            );
// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->ListCreditCards($requestData);

// Write the contents of the response array to the screen for demo purposes.
echo "<pre>";
print_r($PayPalResult);
