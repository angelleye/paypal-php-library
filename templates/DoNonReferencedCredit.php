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
$DNRCFields = array(
					'amt' => '', 						// Required.  Total of order including shipping, handling, and tax.  
					'netamt' => '', 					// Total amount of all items in this transactions.  Subtotal.
					'shippingamt' => '', 				// Total shipping costs on the transaction.
					'taxamt' => '', 					// Sum of tax for all items on the order.
					'currencycode' => '', 				// Required.  Default is USD.  Only valid values are: AUD, CAD, EUR, GBP, JPY, and USD.
					'note' => '' 						// Field used by merchant to record why this credit was issued to the buyer.
				);	
				
$CCDetails = array(
					'creditcardtype' => '', 			// Required.  Type of credit card.  Values can be: Visa, MasterCard, Discover, Amex, Maestro, Solo
					'acct' => '', 						// Required.  Credit card number.  No spaces or punctuation.
					'expdate' => '', 					// Required.  Credit card expiration date.  MMYYYY
					'cvv2' => '', 						// Requirement determined by PayPal profile settings.  Credit Card security digits.
					'startdate' => '', 					// Mo and Yr that Maestro or Solo card was issued.  MMYYYY.
					'issuenumber' => '' 				// Isssue number of Maestro or Solo card.  
);

$PayerInfo = array(
					'email' => '', 						// Email address of payer.
					'firstname' => '', 					// Payer's first name.
					'lastname' => '' 					// Payer's last name.
				);
				
$PayerName = array(
					'salutation' => '', 				// Buyer's salutation.  20 char max.
					'firstname' => '', 					// Buyer's first name.  25 char max.
					'middlename' => '', 				// Buyer's middle name.  25 char max.
					'lastname' => '', 					// Buyer's last name.  25 char max.
					'suffix' => ''						// Buyer's suffix.  12 char max.
				);
				
$BillingAddress = array(
						'street' => '', 				// Required.  First street address.
						'street2' => '', 				// Second street address.
						'city' => '', 					// Required.  Name of City.
						'state' => '', 					// Required. Name of State or Province.
						'countrycode' => '', 			// Required.  Country code.
						'zip' => '', 					// Required.  Postal code of payer.
						'phonenum' => '' 				// Phone Number of payer.  20 char max.
					);
					
$PayPalRequestData = array(
						'DNRCFields'=>$DNRCFields, 
						'CCDetails'=>$CCDetails, 
						'PayerInfo'=>$PayerInfo, 
						'PayerName' =>$PayerName, 
						'BillingAddress'=>$BillingAddress
						);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->DoNonReferencedCredit($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>