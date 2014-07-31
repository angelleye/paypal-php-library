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
$PreapprovalDetailsFields = array(
								  'GetBillingAddress' => '', 									// Opion to get the billing address in the response.  true or false.  Only available with Advanced permissions levels.
								  'PreapprovalKey' => 'PA-52F437596H984705H' 										// Required.  A preapproval key that identifies the preapproval for which you want to retrieve details.  Returned in the PreapprovalResponse
								  );

$PayPalRequestData = array(
					 'PreapprovalDetailsFields' => $PreapprovalDetailsFields
					 );

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->PreapprovalDetails($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>