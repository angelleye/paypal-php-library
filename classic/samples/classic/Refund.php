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
$RefundFields = array(
					  'CurrencyCode' => 'USD', 											// Required.  Must specify code used for original payment.  You do not need to specify if you use a payKey to refund a completed transaction.
					  'PayKey' => '',  													// Required.  The key used to create the payment that you want to refund.
					  'TransactionID' => '9JK32129X69021028', 							// Required.  The PayPal transaction ID associated with the payment that you want to refund.
					  'TrackingID' => ''												// Required.  The tracking ID associated with the payment that you want to refund.
					  );

$Receivers = array();
$Receiver = array(
				  'Email' => 'agb_1296755685_biz@angelleye.com',			// A receiver's email address. 
				  'Amount' => '100.00', 									// Amount to be debited to the receiver's account.
				  'Primary' => '', 											// Set to true to indicate a chained payment.  Only one receiver can be a primary receiver.  Omit this field, or set to false for simple and parallel payments.
				  'InvoiceID' => '', 										// The invoice number for the payment.  This field is only used in Pay API operation.
				  'PaymentType' => 'GOODS'									// The transaction subtype for the payment.  Allowable values are: GOODS, SERVICE
				  );

array_push($Receivers, $Receiver);

$PayPalRequestData = array(
					 'RefundFields' => $RefundFields, 
					 'Receivers' => $Receivers
					 );


// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->Refund($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
