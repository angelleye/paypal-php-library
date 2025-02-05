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

$templateId = 'TEMP-3GK75625L7412105X';    // Required. The ID of the invoice template for which to show details.

$returnArray = $PayPal->GetInvoiceTemplate($templateId);
echo "<pre>";
print_r($returnArray);
