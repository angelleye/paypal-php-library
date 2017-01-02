<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new \angelleye\PayPal\rest\invoice\InvoiceAPI($configArray);

$template_id = '';    // Required.  The ID of the template to delete.

$returnArray = $PayPal->delete_invoice_template($template_id);
echo "<pre>";
var_dump($returnArray);
?>