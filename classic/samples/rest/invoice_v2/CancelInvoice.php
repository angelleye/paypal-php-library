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

$cancelNotification = array(
    'subject' => 'Past due',                      // Subject of the notification.
    'note'    => 'Canceling invoice',             // Note to the payer.
    'send_to_invoicer' => true,                   // Indicates whether to send a copy of the notification to the merchant.
    'send_to_recipient' => true,                  // Indicates whether to send a copy of the notification to the payer.
    'additional_recipients' => array(),             // Applicable for invoices created with Cc emails. If this field is not in the body, all the cc email addresses added as part of the invoice shall be notified else this field can be used to limit the list of email addresses. Note: additional email addresses are not supported.    
);

$InvoiceID = 'INV2-YZZF-D7VN-P2YM-9ZH2';          // Required. Specify the ID of the invoice to cancel.

$returnArray = $PayPal->CancelInvoice($cancelNotification,$InvoiceID);
echo "<pre>";
print_r($returnArray);
