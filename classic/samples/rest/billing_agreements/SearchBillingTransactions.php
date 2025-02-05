<?php

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

$agreementId = 'I-4U6DTW95XNLS';                                // Identifier of the agreement resource for which to list transactions.
/*Required*/
$params = array(
                'start_date' => '2019-01-01',                    // Format : yyyy-mm-dd . The start date of the range of transactions to list.
                'end_date'   => '2019-12-31'                     // Format : yyyy-mm-dd . The end date of the range of transactions to list.
          );

$returnArray = $PayPal->SearchBillingTransactions($agreementId,$params);
echo "<pre>";
print_r($returnArray);
