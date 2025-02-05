<?php

use angelleye\PayPal\rest\invoice\InvoiceAPIv2;

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
$PayPal = new InvoiceAPIv2($configArray);

$InvoiceID = 'INV2-YZZF-D7VN-P2YM-9ZH2';    // Required. The ID of the invoice to send.

$returnArray = $PayPal->SendInvoice($InvoiceID);
echo "<pre>";
print_r($returnArray);
