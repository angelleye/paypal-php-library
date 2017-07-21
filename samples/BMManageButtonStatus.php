<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../autoload.php');

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
$BMManageButtonStatusFields = array
						(
						'hostedbuttonid' => 'C3TRVEHPLKZ2N', 			// Required.  The ID of the hosted button whose inventory information you want to obtain.
                        'buttonstatus' => 'DELETE',               // Required.  The new status of the button.  Values are:  DELETE
						);
				
$PayPalRequestData = array(
    'BMManageButtonStatusFields' => $BMManageButtonStatusFields,
);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->BMManageButtonStatus($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);