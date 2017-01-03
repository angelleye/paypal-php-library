<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new \angelleye\PayPal\rest\invoice\InvoiceAPI($configArray);

$InvoiceID = 'INV2-G69Z-7NQ7-QRLN-EWP2';    // Required.  The ID of the invoice for which to show details.

$returnArray = $PayPal->delete_invoice($InvoiceID);
echo "<pre>";
var_dump($returnArray);
?>