<?php

/**
 * A sale is a completed payment. 
 * Shows details for a sale, by ID. Returns only sales that were created through the REST API.
 */

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

$sale_id = ''; // The ID of the sale for which to show details.

$returnArray = $PayPal->GetSale($sale_id);
echo "<pre>";
print_r($returnArray);