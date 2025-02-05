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

$InvoiceID = 'INV2-G69Z-7NQ7-QRLN-EWP2';    // Required.  The ID of the invoice for which to show details.

$returnArray = $PayPal->DeleteInvoice($InvoiceID);
echo "<pre>";
print_r($returnArray);