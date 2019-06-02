<?php
// #GetPaymentList
// This sample code demonstrate how you can
// retrieve a list of all Payment resources
// you've created using the Payments API.
// Note various query parameters that you can
// use to filter, and paginate through the
// payments list.

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

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$params = array(
    'count'       => '',            // Default: 10. The number of items to list in the response.
    'start_index' => '',            // The ID of the starting resource in the response. When results are paged, you can use the next_id value as the start_id to continue with the next set of results.
    'start_id'    => '',            // The start index of the resources to return. Typically used to jump to a specific position in the resource history based on its cart. Example for starting at the second item in a list of results: ?start_index=2
    'start_time'  => '',            // The date and time when the resource was created. Indicates the start of a range of results. Example: start_time=2013-03-06T11:00:00Z
    'end_time'    => '',            // The date and time when the resource was created. Indicates the end of a range of results. Format: date-time.
);

$returnArray = $PayPal->ListPayments($params);
echo "<pre>";
print_r($returnArray);
