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

$template_id = 'TEMP-2F480752N0057011C';    // Required. The ID of the template to delete.

$returnArray = $PayPal->DeleteInvoiceTemplate($template_id);
echo "<pre>";
print_r($returnArray);