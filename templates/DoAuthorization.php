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
$DAFields = array(
				'transactionid' => '', 					// Required.  The value of the order's transaction ID number returned by PayPal.  
				'amt' => '', 							// Required. Must have two decimal places.  Decimal separator must be a period (.) and optional thousands separator must be a comma (,)
				'transactionentity' => '', 				// Type of transaction to authorize.  The only allowable value is Order, which means that the transaction represents a customer order that can be fulfilled over 29 days.
				'currencycode' => '', 					// Three-character currency code.
				'msgsubid' => ''						// A message ID used for idempotence to uniquely identify a message.  
			);
			
$PayPalRequestData = array('DAFields'=>$DAFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->DoAuthorization($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>