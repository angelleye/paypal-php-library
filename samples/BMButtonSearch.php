<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../includes/paypal.class.php');

// Create PayPal object.
$PayPalConfig = array(
					'Sandbox' => $sandbox,
					'APIUsername' => $api_username,
					'APIPassword' => $api_password,
					'APISignature' => $api_signature
					);

$PayPal = new PayPal($PayPalConfig);

// Prepare request arrays
$BMButtonSearchFields = array
						(
						'startdate' => '2009-01-01T00:00:00Z', 			// Required.  Starting date for the search.  UTC/GMT format: 2009-08-24T05:38:48Z
						'enddate' => ''				// Ending date for the search.  UTC/GMT format: 2010-05-01T05:38:48Z  
						);
				
$PayPalRequestData = array('BMButtonSearchFields'=>$BMButtonSearchFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->BMButtonSearch($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>