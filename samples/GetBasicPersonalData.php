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
					  'APISubject' => 'sandbo_1204199080_biz@angelleye.com',
                      'PrintHeaders' => $print_headers,
                      'LogResults' => $log_results,
                      'LogPath' => $log_path,
					);

$PayPal = new angelleye\PayPal\Adaptive($PayPalConfig);

// Prepare request arrays
$AttributeList = array(
						'http://axschema.org/namePerson/first',
						'http://axschema.org/namePerson/last',
						'http://axschema.org/contact/email',
						'http://axschema.org/contact/fullname',
						'http://openid.net/schema/company/name',
						'http://axschema.org/contact/country/home',
						'https://www.paypal.com/webapps/auth/schema/payerID'
					);
					
// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->GetBasicPersonalData($AttributeList);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>