<?php
// Include required library files.
require_once('includes/config.php');
require_once('includes/paypal.class.php');

// Create PayPal object.
$PayPalConfig = array(
					  'Sandbox' => $sandbox,
					  'DeveloperAccountEmail' => $developer_account_email,
					  'ApplicationID' => $application_id,
					  'DeviceID' => $device_id,
					  'IPAddress' => $_SERVER['REMOTE_ADDR'],
					  'APIUsername' => $api_username,
					  'APIPassword' => $api_password,
					  'APISignature' => $api_signature,
					  'APISubject' => $api_subject
					);

$PayPal = new PayPal_Adaptive($PayPalConfig);

// Prepare request arrays
$CancelInvoiceFields = array(
							'InvoiceID' => '', 			// ID of the invoice.
							'Subject' => '', 			// Subject of the cancelation notification.
							'NoteForPayer' => '', 		// Note to send the payer within the cancelation notification.
							'SendCopyToMerchant' => ''	// Indicates whether to send a copy of the cancelation notification to the merchant.  Values are:  true/false
							);

$PayPalRequestData = array('CancelInvoiceFields' => $CancelInvoiceFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->CancelInvoice($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>