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

$fields= array(
    "fields" => ""       // Default: all. The fields to return in the response. Value is all or none. Specify none to return only the template name, ID, and default attributes.
    );

$returnArray = $PayPal->GetAllInvoiceTemplates($fields);
echo "<pre>";
print_r($returnArray);
