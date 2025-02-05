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
$Scope = array(
				'EXPRESS_CHECKOUT', 
				'DIRECT_PAYMENT', 
				'SETTLEMENT_CONSOLIDATION', 
				'SETTLEMENT_REPORTING', 
				'AUTH_CAPTURE', 
				'MOBILE_CHECKOUT', 
				'BILLING_AGREEMENT', 
				'REFERENCE_TRANSACTION', 
				'AIR_TRAVEL', 
				'MASS_PAY', 
				'TRANSACTION_DETAILS',
				'TRANSACTION_SEARCH',
				'RECURRING_PAYMENTS',
				'ACCOUNT_BALANCE',
				'ENCRYPTED_WEBSITE_PAYMENTS',
				'REFUND',
				'NON_REFERENCED_CREDIT',
				'BUTTON_MANAGER',
				'MANAGE_PENDING_TRANSACTION_STATUS',
				'RECURRING_PAYMENT_REPORT',
				'EXTENDED_PRO_PROCESSING_REPORT',
				'EXCEPTION_PROCESSING_REPORT',
				'ACCOUNT_MANAGEMENT_PERMISSIONS',
				'ACCESS_BASIC_PERSONAL_DATA',
				'ACCESS_ADVANCED_PERSONAL_DATA'
				);

$RequestPermissionsFields = array(
								'Scope' => $Scope, 				// Required.   
								'Callback' => ''			// Required.  Your callback function that specifies actions to take after the account holder grants or denies the request.
								);
								
$PayPalRequestData = array('RequestPermissionsFields' => $RequestPermissionsFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->RequestPermissions($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
