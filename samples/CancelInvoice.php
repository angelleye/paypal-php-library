<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../includes/paypal.class.php');

// Create PayPal object.
$PayPalConfig = array(
					  'Sandbox' => $sandbox,
					  'DeveloperAccountEmail' => $developer_account_email,
					  'ApplicationID' => $application_id,
					  'DeviceID' => '',
					  'IPAddress' => $_SERVER['REMOTE_ADDR'],
					  'APIUsername' => $api_username,
					  'APIPassword' => $api_password,
					  'APISignature' => $api_signature,
					  'APISubject' => $api_subject
					);

$PayPal = new PayPal_Adaptive($PayPalConfig);

// Prepare request arrays
$CancelInvoiceFields = array(
							'InvoiceID' => 'INV2-3283-QFCE-5L2E-4FCH', 			// ID of the invoice.
							'Subject' => 'Invoice has been canceled.', 			// Subject of the cancelation notification.
							'NoteForPayer' => 'Note for Payer.', 		// Note to send the payer within the cancelation notification.
							'SendCopyToMerchant' => 'true'	// Indicates whether to send a copy of the cancelation notification to the merchant.  Values are:  true/false
							);

$PayPalRequestData = array('CancelInvoiceFields' => $CancelInvoiceFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->CancelInvoice($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>