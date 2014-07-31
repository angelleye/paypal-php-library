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
$BOAFields = array(
				   'profileid' => '', 				// Required.  Recurring payments profile ID returned from CreateRecurringPaymentsProfile.
				   'amt' => '', 					// The amount to bill.  Must be less than or equal to the current oustanding balance.  Default is to collect entire amount.
				   'note' => ''						// Note about the reason for the non-scheduled payment.  EC profiles will show this message in the email notification to the buyer and can be seen in the details page by both buyer and seller.
				   );
				   
$PayPalRequestData = array('BOAFields'=>$BOAFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->BillOutstandingAmount($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>