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
$PreapprovalFields = array(
						   'CancelURL' => '',  								// Required.  URL to send the browser to after the user cancels.
						   'CurrencyCode' => '', 							// Required.  Currency Code.
						   'DateOfMonth' => '', 							// The day of the month on which a monthly payment is to be made.  0 - 31.  Specifying 0 indiciates that payment can be made on any day of the month.
						   'DayOfWeek' => '', 								// The day of the week that a weekly payment should be made.  Allowable values: NO_DAY_SPECIFIED, SUNDAY, MONDAY, TUESDAY, WEDNESDAY, THURSDAY, FRIDAY, SATURDAY
						   'EndingDate' => '', 								// Required.  The last date for which the preapproval is valid.  It cannot be later than one year from the starting date.
						   'IPNNotificationURL' => '', 						// The URL for IPN notifications.
						   'MaxAmountPerPayment' => '', 					// The preapproved maximum amount per payment.  Cannot exceed the preapproved max total amount of all payments.
						   'MaxNumberOfPayments' => '', 					// The preapproved maximum number of payments.  Cannot exceed the preapproved max total number of all payments. 
						   'MaxTotalAmountOfPaymentsPerPeriod' => '', 	// The preapproved maximum number of all payments per period.
						   'MaxTotalAmountOfAllPayments' => '', 			// The preapproved maximum total amount of all payments.  Cannot exceed $2,000 USD or the equivalent in other currencies.
						   'Memo' => '', 									// A note about the preapproval.
						   'PaymentPeriod' => '', 							// The pament period.  One of the following:  NO_PERIOD_SPECIFIED, DAILY, WEEKLY, BIWEEKLY, SEMIMONTHLY, MONTHLY, ANNUALLY
						   'PinType' => '', 								// Whether a personal identification number is required.  It is one of the following:  NOT_REQUIRED, REQUIRED
						   'ReturnURL' => '', 								// URL to return the sender to after approving at PayPal.
						   'SenderEmail' => '', 							// Sender's email address.  If not specified, the email address of the sender who logs on to approve is used.
						   'StartingDate' => '', 							// Required.  First date for which the preapproval is valid.  Cannot be before today's date or after the ending date.
						   'FeesPayer' => '', 								// The payer of the PayPal fees.  Values are:  SENDER, PRIMARYRECEIVER, EACHRECEIVER, SECONDARYONLY
						   'DisplayMaxTotalAmount' => ''					// Whether to display the max total amount of this preapproval.  Values are:  true/false
						   );

$ClientDetailsFields = array(
							 'CustomerID' => '', 						// Your ID for the sender.
							 'CustomerType' => '', 						// Your ID of the type of customer.
							 'GeoLocation' => '', 						// Sender's geographic location.
							 'Model' => '', 							// A sub-id of the application
							 'PartnerName' => ''						// Your organization's name or ID.
							 );

$PayPalRequestData = array(
					 'PreapprovalFields' => $PreapprovalFields, 
					 'ClientDetailsFields' => $ClientDetailsFields
					 );


// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->Preapproval($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>