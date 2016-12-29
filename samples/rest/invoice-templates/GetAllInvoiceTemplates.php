<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new \angelleye\PayPal\rest\invoice\InvoiceAPI($configArray);

$fields= array(
    "fields" => "all"       // Default: all. The fields to return in the response. Value is all or none. Specify none to return only the template name, ID, and default attributes.
    );

$returnArray = $PayPal->get_all_invoice_templates($fields);
echo "<pre>";
var_dump($returnArray);
?>