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
$SetFundingSourceConfirmedFields = array(
										'AccountID' => '', 													// The ID number of the PayPal account for which a bank account is added.  You must specify either AccountID or EmailAddress for this request.
										'EmailAddress' => '', 												// The email address of the PayPal account holder.  You must specify either AccountID or EmailAddress.
										'FundingSourceKey' => ''											// The funding source key reeturned in the AddBankAccount or AddPaymentCard response.
										);

$PayPalRequestData = array('SetFundingSourceConfirmedFields' => $SetFundingSourceConfirmedFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->SetFundingSourceConfirmed($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>