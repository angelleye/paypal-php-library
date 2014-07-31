<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../autoload.php');


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
$PayRequestFields = array(
						'ActionType' => 'PAY', 								// Required.  Whether the request pays the receiver or whether the request is set up to create a payment request, but not fulfill the payment until the ExecutePayment is called.  Values are:  PAY, CREATE, PAY_PRIMARY
						'CancelURL' => $domain.'paypal/class/1.2/Pay_Cancel.php', 									// Required.  The URL to which the sender's browser is redirected if the sender cancels the approval for the payment after logging in to paypal.com.  1024 char max.
						'CurrencyCode' => 'USD', 								// Required.  3 character currency code.
						'FeesPayer' => '', 									// The payer of the fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
						'IPNNotificationURL' => '', 						// The URL to which you want all IPN messages for this payment to be sent.  1024 char max.
						'Memo' => '', 										// A note associated with the payment (text, not HTML).  1000 char max
						'Pin' => '', 										// The sener's personal id number, which was specified when the sender signed up for the preapproval
						'PreapprovalKey' => '', 							// The key associated with a preapproval for this payment.  The preapproval is required if this is a preapproved payment.  
						'ReturnURL' => $domain.'paypal/class/1.2/Pay_Return.php', 									// Required.  The URL to which the sener's browser is redirected after approvaing a payment on paypal.com.  1024 char max.
						'ReverseAllParallelPaymentsOnError' => '', 			// Whether to reverse paralel payments if an error occurs with a payment.  Values are:  TRUE, FALSE
						'SenderEmail' => '', 								// Sender's email address.  127 char max.
						'TrackingID' => ''									// Unique ID that you specify to track the payment.  127 char max.
						);
						
$ClientDetailsFields = array(
						'CustomerID' => '', 								// Your ID for the sender  127 char max.
						'CustomerType' => '', 								// Your ID of the type of customer.  127 char max.
						'GeoLocation' => '', 								// Sender's geographic location
						'Model' => '', 										// A sub-identification of the application.  127 char max.
						'PartnerName' => 'Always Give Back'									// Your organization's name or ID
						);
						
$FundingTypes = array('ECHECK', 'BALANCE', 'CREDITCARD');

$Receivers = array();
$Receiver = array(
				'Amount' => '10.00', 											// Required.  Amount to be paid to the receiver.
				'Email' => 'sandbo_1204199080_biz@angelleye.com', 												// Receiver's email address. 127 char max.
				'InvoiceID' => '', 											// The invoice number for the payment.  127 char max.
				'PaymentType' => '', 										// Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
				'PaymentSubType' => '', 									// The transaction subtype for the payment.
				'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
				'Primary' => ''												// Whether this receiver is the primary receiver.  Values are boolean:  TRUE, FALSE
				);
array_push($Receivers,$Receiver);

$Receiver = array(
				'Amount' => '5.00', 											// Required.  Amount to be paid to the receiver.
				'Email' => 'usb_1329725429_biz@angelleye.com', 												// Receiver's email address. 127 char max.
				'InvoiceID' => '123-ABCDEF', 											// The invoice number for the payment.  127 char max.
				'PaymentType' => '', 										// Transaction type.  Values are:  GOODS, SERVICE, PERSONAL, CASHADVANCE, DIGITALGOODS
				'PaymentSubType' => '', 									// The transaction subtype for the payment.
				'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => ''), // Receiver's phone number.   Numbers only.
				'Primary' => ''												// Whether this receiver is the primary receiver.  Values are boolean:  TRUE, FALSE
				);
array_push($Receivers,$Receiver);

$SenderIdentifierFields = array(
								'UseCredentials' => ''						// If TRUE, use credentials to identify the sender.  Default is false.
								);
								
$AccountIdentifierFields = array(
								'Email' => '', 								// Sender's email address.  127 char max.
								'Phone' => array('CountryCode' => '', 'PhoneNumber' => '', 'Extension' => '')								// Sender's phone number.  Numbers only.
								);
								
$PayPalRequestData = array(
					'PayRequestFields' => $PayRequestFields, 
					'ClientDetailsFields' => $ClientDetailsFields, 
					//'FundingTypes' => $FundingTypes, 
					'Receivers' => $Receivers, 
					'SenderIdentifierFields' => $SenderIdentifierFields, 
					'AccountIdentifierFields' => $AccountIdentifierFields
					);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->Pay($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>