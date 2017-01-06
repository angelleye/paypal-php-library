<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new \angelleye\PayPal\rest\invoice\InvoiceAPI($configArray);

$remindNotification = array(
    'Subject' => 'Past due',                      // Subject of the notification.
    'Note'    => 'Please pay soon',                      // Note to the payer.
    'SendToMerchant' => 'true',               // Indicates whether to send a copy of the notification to the merchant.
    'SendToPayer' => '',                     // Indicates whether to send a copy of the notification to the payer.
    'CcEmails' => '',                        // Applicable for invoices created with Cc emails. If this field is not in the body, all the cc email addresses added as part of the invoice shall be notified else this field can be used to limit the list of email addresses. Note: additional email addresses are not supported.    
);

$InvoiceID = 'INV2-GASZ-DB7C-NSRC-S9V6';    // Required. Specify the ID of the invoice to remind.



$returnArray = $PayPal->remind_invoice($remindNotification,$InvoiceID);
echo "<pre>";
var_dump($returnArray);
?>