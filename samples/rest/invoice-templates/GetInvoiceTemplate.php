<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new \angelleye\PayPal\rest\invoice\InvoiceAPI($configArray);

$templateId = 'TEMP-3GK75625L7412105X';    // Required. The ID of the invoice template for which to show details.

$returnArray = $PayPal->get_invoice_template($templateId);
echo "<pre>";
var_dump($returnArray);
?>