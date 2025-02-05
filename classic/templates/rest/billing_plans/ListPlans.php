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

$parameters = array(
    'page_size'      => '',                // The number of billing plans to list on a single page. For example, if page_size is 10, each page shows ten billing plans. A valid value is a non-negative, non-zero integer. Default is 10.
    'page'           => '',                // The zero-indexed number of the first page to return to begin the set of pages that are returned in the response. Default is 0.
    'status'         => '',                // Possible values: CREATED, ACTIVE, INACTIVE, DELETED. Filters the billing plans in the response by a plan status. Value is CREATED for created plans, ACTIVE for active plans, INACTIVE for inactive plans, or DELETED for deleted plans.
    'total_required' => ''                 // Value is yes or no. Indicates whether to return the total_items and total_pages fields in the response.  
);

$returnArray = $PayPal->ListPlans($parameters);
echo "<pre>";
print_r($returnArray);
