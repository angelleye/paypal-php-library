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
$PayPal = new \angelleye\PayPal\rest\invoice\InvoiceAPI($configArray);

$parameters = array(
    'page'                  => '',                // A zero-relative index of the list of merchant invoices.
    'page_size'             => '',                // The number of invoices to list beginning with the specified page.
    'total_count_required ' => '',                // Indicates whether the total count appears in the response. Default is false.
);

$returnArray = $PayPal->ListInvoice($parameters);
echo "<pre>";
print_r($returnArray);
