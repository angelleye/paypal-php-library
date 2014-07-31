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
$DVFields = array(
					'authorizationid' => '', 				// Required.  The value of the original authorization ID returned by PayPal.  NOTE:  If voiding a transaction that has been reauthorized, use the ID from the original authorization, not the reauth.
					'note' => '',  							// An information note about this void that is displayed to the payer in an email and in his transaction history.  255 char max.
					'msgsubid' => ''						// A message ID used for idempotence to uniquely identify a message.
				);
				
$PayPalRequestData = array('DVFields'=>$DVFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->DoVoid($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>