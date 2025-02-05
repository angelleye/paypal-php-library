<?php
// Include required library files.
require_once('../../includes/config.php');
require_once('../../autoload.php');

// Create PayPal object.
$PayPalConfig = array(
					'Sandbox' => $sandbox,
					'APIUsername' => $api_username,
					'APIPassword' => $api_password,
					'APISignature' => $api_signature, 
					'PrintHeaders' => $print_headers,
                    'LogResults' => $log_results,
                    'LogPath' => $log_path,
					);

$PayPal = new angelleye\PayPal\PayPal($PayPalConfig);

// Prepare request arrays
$GRPPDFields = array(
				   'profileid' => 'I-YMGN1WN4YY9E'			// Profile ID of the profile you want to get details for.
				   );
				   
$PayPalRequestData = array('GRPPDFields'=>$GRPPDFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->GetRecurringPaymentsProfileDetails($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
