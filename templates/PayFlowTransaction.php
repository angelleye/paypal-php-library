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
                        'PrintHeaders' => $print_headers
					  );

$PayPal = new angelleye\PayPal\PayFlow($PayPalConfig);

// Prepare request arrays
$PayPalRequestData = array(
		'tender'=>'', 				// Required.  The method of payment.  Values are: A = ACH, C = Credit Card, D = Pinless Debit, K = Telecheck, P = PayPal
		'trxtype'=>'', 				// Required.  Indicates the type of transaction to perform.  Values are:  A = Authorization, B = Balance Inquiry, C = Credit, D = Delayed Capture, F = Voice Authorization, I = Inquiry, L = Data Upload, N = Duplicate Transaction, S = Sale, V = Void
		'acct'=>'', 				// Required for credit card transaction.  Credit card or purchase card number.
		'expdate'=>'', 				// Required for credit card transaction.  Expiration date of the credit card.  Format:  MMYY
		'amt'=>'', 					// Required.  Amount of the transaction.  Must have 2 decimal places. 
		'dutyamt'=>'', 				//
		'freightamt'=>'', 			//
		'taxamt'=>'', 				//
		'taxexempt'=>'', 			// 
		'comment1'=>'', 			// Merchant-defined value for reporting and auditing purposes.  128 char max
		'comment2'=>'', 			// Merchant-defined value for reporting and auditing purposes.  128 char max
		'cvv2'=>'', 				// A code printed on the back of the card (or front for Amex)
		'recurring'=>'', 			// Identifies the transaction as recurring.  One of the following values:  Y = transaction is recurring, N = transaction is not recurring. 
		'swipe'=>'', 				// Required for card-present transactions.  Used to pass either Track 1 or Track 2, but not both.
		'orderid'=>'', 				// Checks for duplicate order.  If you pass orderid in a request and pass it again in the future the response returns DUPLICATE=2 along with the orderid
		'billtoemail'=>'', 			// Account holder's email address.
		'billtophonenum'=>'', 		// Account holder's phone number.
		'billtofirstname'=>'', 		// Account holder's first name.
		'billtomiddlename'=>'', 	// Account holder's middle name.
		'billtolastname'=>'', 		// Account holder's last name.
		'billtostreet'=>'', 		// The cardholder's street address (number and street name).  150 char max
		'billtocity'=>'', 			// Bill to city.  45 char max
		'billtostate'=>'', 			// Bill to state.  
		'billtozip'=>'', 			// Account holder's 5 to 9 digit postal code.  9 char max.  No dashes, spaces, or non-numeric characters
		'billtocountry'=>'', 		// Bill to Country.  3 letter country code.
		'shiptofirstname'=>'', 		// Ship to first name.  30 char max
		'shiptomiddlename'=>'', 	// Ship to middle name. 30 char max
		'shiptolastname'=>'', 		// Ship to last name.  30 char max
		'shiptostreet'=>'', 		// Ship to street address.  150 char max
		'shiptostate'=>'', 			// Ship to state.
		'shiptozip'=>'', 			// Ship to postal code.  10 char max
		'shiptocountry'=>'', 		// Ship to country code.  3 char code.
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