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
$RTFields = array(
					'transactionid' => '0DX90678PB325572N', 							// Required.  PayPal transaction ID for the order you're refunding.
					'invoiceid' => '', 								// Your own invoice tracking number.
					'refundtype' => 'Full', 							// Required.  Type of refund.  Must be Full, Partial, or Other.
					'amt' => '', 									// Refund Amt.  Required if refund type is Partial.  
					'currencycode' => '', 							// Three-letter currency code.  Required for Partial Refunds.  Do not use for full refunds.
					'note' => '',  									// Custom memo about the refund.  255 char max.
					'retryuntil' => '', 							// Maximum time until you must retry the refund.  Note:  this field does not apply to point-of-sale transactions.
					'refundsource' => '', 							// Type of PayPal funding source (balance or eCheck) that can be used for auto refund.  Values are:  any, default, instant, eCheck
					'merchantstoredetail' => '', 					// Information about the merchant store.
					'refundadvice' => '', 							// Flag to indicate that the buyer was already given store credit for a given transaction.  Values are:  1/0
					'refunditemdetails' => '', 						// Details about the individual items to be returned.
					'storeid' => '', 								// ID of a merchant store.  This field is required for point-of-sale transactions.  50 char max.
					'terminalid' => ''								// ID of the terminal.  50 char max.
				);
				
$PayPalRequestData = array('RTFields'=>$RTFields);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->RefundTransaction($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>