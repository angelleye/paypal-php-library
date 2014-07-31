<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../autoload.php');

// Create PayPal object.
$PayPalConfig = array(
						'Sandbox' => $sandbox, 
						'APIUsername' => $payflow_username, 
						'APIPassword' => $payflow_password, 
						'APIVendor' => $payflow_vendor, 
						'APIPartner' => $payflow_partner, 
						'Verbosity' => 'HIGH',		// Detail level for API response.  Values are:  LOW, MEDIUM, HIGH
                        'PrintHeaders' => $print_headers,
                        'LogResults' => $log_results,
                        'LogPath' => $log_path,
					  );

$PayPal = new angelleye\PayPal\PayFlow($PayPalConfig);

// Prepare request arrays
$PayPalRequestData = array(
		'tender'=>'C', 				// Required.  The method of payment.  Values are: A = ACH, C = Credit Card, D = Pinless Debit, K = Telecheck, P = PayPal
		'trxtype'=>'S', 				// Required.  Indicates the type of transaction to perform.  Values are:  A = Authorization, B = Balance Inquiry, C = Credit, D = Delayed Capture, F = Voice Authorization, I = Inquiry, L = Data Upload, N = Duplicate Transaction, S = Sale, V = Void
		'acct'=>'4012888888881881', 				// Required for credit card transaction.  Credit card or purchase card number.
		'expdate'=>'1215', 				// Required for credit card transaction.  Expiration date of the credit card.  Format:  MMYY
		'amt'=>'10.00', 					// Required.  Amount of the transaction.  Must have 2 decimal places. 
		'dutyamt'=>'', 				//
		'freightamt'=>'5.00', 			//
		'taxamt'=>'2.50', 				//
		'taxexempt'=>'', 			// 
		'comment1'=>'This is a test!', 			// Merchant-defined value for reporting and auditing purposes.  128 char max
		'comment2'=>'This is only a test!', 			// Merchant-defined value for reporting and auditing purposes.  128 char max
		'cvv2'=>'123', 				// A code printed on the back of the card (or front for Amex)
		'recurring'=>'', 			// Identifies the transaction as recurring.  One of the following values:  Y = transaction is recurring, N = transaction is not recurring. 
		'swipe'=>'', 				// Required for card-present transactions.  Used to pass either Track 1 or Track 2, but not both.
		'orderid'=>'', 				// Checks for duplicate order.  If you pass orderid in a request and pass it again in the future the response returns DUPLICATE=2 along with the orderid
		'billtoemail'=>'sandbox@testdomain.com', 			// Account holder's email address.
		'billtophonenum'=>'816-555-5555', 		// Account holder's phone number.
		'billtofirstname'=>'Tester', 		// Account holder's first name.
		'billtomiddlename'=>'', 	// Account holder's middle name.
		'billtolastname'=>'Testerson', 		// Account holder's last name.
		'billtostreet'=>'123 Test Ave.', 		// The cardholder's street address (number and street name).  150 char max
		'billtocity'=>'Kansas City', 			// Bill to city.  45 char max
		'billtostate'=>'MO', 			// Bill to state.  
		'billtozip'=>'64111', 			// Account holder's 5 to 9 digit postal code.  9 char max.  No dashes, spaces, or non-numeric characters
		'billtocountry'=>'US', 		// Bill to Country Code.
		'shiptofirstname'=>'Tester', 		// Ship to first name.  30 char max
		'shiptomiddlename'=>'', 	// Ship to middle name. 30 char max
		'shiptolastname'=>'Testerson', 		// Ship to last name.  30 char max
		'shiptostreet'=>'123 Test Ave.', 		// Ship to street address.  150 char max
		'shiptocity'=>'Kansas City', 					// Ship to city.
		'shiptostate'=>'MO', 			// Ship to state.
		'shiptozip'=>'64111', 			// Ship to postal code.  10 char max
		'shiptocountry'=>'US', 		// Ship to country code.
		'origid'=>'', 				// Required by some transaction types.  ID of the original transaction referenced.  The PNREF parameter returns this ID, and it appears as the Transaction ID in PayPal Manager reports.  
		'custref'=>'', 				// 
		'custcode'=>'', 			// 
		'custip'=>'', 				// 
		'invnum'=>'', 				// 
		'ponum'=>'', 				// 
		'starttime'=>'', 			// For inquiry transaction when using CUSTREF to specify the transaction.
		'endtime'=>'', 				// For inquiry transaction when using CUSTREF to specify the transaction.
		'securetoken'=>'', 			// Required if using secure tokens.  A value the Payflow server created upon your request for storing transaction data.  32 char
		'partialauth'=>'', 			// Required for partial authorizations.  Set to Y to submit a partial auth.    
		'authcode'=>'' 			// Rrequired for voice authorizations.  Returned only for approved voice authorization transactions.  AUTHCODE is the approval code received over the phone from the processing network.  6 char max
		);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->ProcessTransaction($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>