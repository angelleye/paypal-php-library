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

$remindNotification = array(
    'Subject' => 'Past due',                        // Subject of the notification.
    'Note'    => 'Please pay soon',                 // Note to the payer.
    'SendToMerchant' => 'true',                     // Indicates whether to send a copy of the notification to the merchant.
    'SendToPayer' => '',                            // Indicates whether to send a copy of the notification to the payer.
    'CcEmails' => '',                               // Applicable for invoices created with Cc emails. If this field is not in the body, all the cc email addresses added as part of the invoice shall be notified else this field can be used to limit the list of email addresses. Note: additional email addresses are not supported.    
);

$InvoiceID = 'INV2-JZ52-L8SK-U4L2-RF8M';            // Required. Specify the ID of the invoice to remind.

$returnArray = $PayPal->RemindInvoice($remindNotification,$InvoiceID);
echo "<pre>";
print_r($returnArray);
