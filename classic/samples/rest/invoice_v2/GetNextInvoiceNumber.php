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

$returnArray = $PayPal->GetNextInvoiceNumber();
echo "<pre>";
print_r($returnArray);
