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
$GetUserAgreementFields = array(
								'CountryCode' => '', 					// The code for the country in which the user account is located. You do not need to provide this country code if you are passing the createAccount key.
								'CreateAccountKey' => '', 				// The key returned for this account in the CreateAccountResponse message in the createAccountKey field. If you specify this key, do not pass a country code or language code. Doing so will result in an error.
								'LanguageCode' => '' 					// The code indicating the language to be used for the agreement.
								);

$PayPalRequestData = array('GetUserAgreementFields' => $GetUserAgreementFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->GetUserAgreement($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);