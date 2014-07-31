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
$BAUFields = array(
				   'referenceid' => '', 							// Required. An ID, such as a billing agreement ID or a reference transaction ID that is associated with a billing agreement.
				   'billingagreementstatus' => '', 					// The current status of the billing agreement, which is one of the following values: Active or Canceled.
				   'billingagreementdescription' => '', 			// Description of goods or services associated with the billing agreement, which is required for each recurring payment billing agreement. PayPal recommends that the description contain a brief summary of the billing agreement terms and conditions. For example, customer will be billed at "9.99 per month for 2 years". 127 Car max.
				   'billingagreementcustom' => ''					// Custom annotation field for your own use.  256 char max.
				   );
				   
$PayPalRequestData = array('BAUFields'=>$BAUFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->BillAgreementUpdate($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>