<?php
// Include required library files.
require_once('../../includes/config.php');
require_once('../../autoload.php');


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
					  'APISubject' => $api_subject,
                      'PrintHeaders' => $print_headers, 
					  'LogResults' => $log_results, 
					  'LogPath' => $log_path,
					);

$PayPal = new angelleye\PayPal\Adaptive($PayPalConfig);

// Prepare request arrays
$MarkInvoiceAsPaidFields = array(
								'InvoiceID' => 'INV2-GZWT-JZHS-FR3G-EDWA', 			// Required.  ID of the invoice to mark paid.
								'Method' => 'Cash', 			// Method t hat can be used to mark an invoice as paid when the payer p ays offline.  Values are:  BankTransfer, Cash, Check, CreditCard, DebitCard, Other, PayPal, WireTransfer
								'Note' => 'This is a test note.', 				// Optional note associated with the payment.
								'Date' => '2012-02-19'				// Date the invoice was paid.
							);

$PayPalRequestData = array('MarkInvoiceAsPaidFields' => $MarkInvoiceAsPaidFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->MarkInvoiceAsPaid($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
