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
$DCFields = array(
					'authorizationid' => '', 				// Required. The authorization identification number of the payment you want to capture. This is the transaction ID returned from DoExpressCheckoutPayment or DoDirectPayment.
					'amt' => '', 							// Required. Must have two decimal places.  Decimal separator must be a period (.) and optional thousands separator must be a comma (,)
					'completetype' => '', 					// Required.  The value Complete indiciates that this is the last capture you intend to make.  The value NotComplete indicates that you intend to make additional captures.
					'currencycode' => '', 					// Three-character currency code
					'invnum' => '', 						// Your invoice number
					'note' => '', 							// Informational note about this setlement that is displayed to the buyer in an email and in his transaction history.  255 character max.
					'softdescriptor' => '', 				// Per transaction description of the payment that is passed to the customer's credit card statement.
					'msgsubid' => '', 						// A message ID used for idempotence to uniquely identify a message.  This ID can later be used to request the latest results for a previous request without generating a new request.  Examples of this include requests due to timeouts or errors during the original request.  38 Char Max
					'storeid' => '', 						// ID of the merchant store.  This field is required for point-of-sale transactions.  Max: 50 char
					'terminalid' => ''						// ID of the terminal.  50 char max.  
				);
				
$PayPalRequestData = array('DCFields' => $DCFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->DoCapture($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>