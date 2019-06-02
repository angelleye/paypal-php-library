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

$PayPal = new \angelleye\PayPal\rest\customerdisputes\CustomerDisputesAPI($configArray);

/**
 *  Note for parameter : You can specify either but not both 
 *  the start_time and disputed_transaction_id query parameter. 
 *  If you omit the start time, default is the current date and time.
 */

$parameters = array(
    'disputed_transaction_id' => '2EV46355LC1739201', // Filters the disputes in the response by a transaction, by ID. You can specify either but not both the start_time and disputed_transaction_id query parameter.
    //'start_time' => '',                             // Format yyyy-MM-ddTHH:mm:ss.SSSZ. For example 2018-10-03T09:53:06.000Z, Filters the disputes in the response by a creation date and time. The start time must be within the last 180 days. 
    'page_size' => '1',                               // Default: 10. Minimum value: 1. Maximum value: 50. Limits the number of disputes in the response to this value.
    'next_page_token' => '',                          // The token that describes the next page of results to fetch. The list disputes call returns this token in the HATEOAS links in the response. If you omit this parameter, the API returns the first page of data.
    'dispute_state' => 'OPEN_INQUIRIES'               // REQUIRED_ACTION | REQUIRED_OTHER_PARTY_ACTION | UNDER_PAYPAL_REVIEW | RESOLVED | OPEN_INQUIRIES | Filters the disputes in the response by a state.   
);
$response = $PayPal->ListDisputes($parameters);

echo "<pre>";
print_r($response);
exit;
