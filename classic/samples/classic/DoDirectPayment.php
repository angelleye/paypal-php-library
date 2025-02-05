<?php
require_once('../../includes/config.php');
require_once('../../autoload.php');

$PayPalConfig = array(
					'Sandbox' => $sandbox,
					'APIUsername' => $api_username,
					'APIPassword' => $api_password,
					'APISignature' => $api_signature, 
					'PrintHeaders' => $print_headers,
                    'LogResults' => $log_results,
                    'LogPath' => $log_path,					);

$PayPal = new angelleye\PayPal\PayPal($PayPalConfig);

$DPFields = array(
					'paymentaction' => 'Sale', 						// How you want to obtain payment.  Authorization indicates the payment is a basic auth subject to settlement with Auth & Capture.  Sale indicates that this is a final sale for which you are requesting payment.  Default is Sale.
					'ipaddress' => $_SERVER['REMOTE_ADDR'], 							// Required.  IP address of the payer's browser.
					'returnfmfdetails' => '1' 					// Flag to determine whether you want the results returned by FMF.  1 or 0.  Default is 0.
				);
				
$CCDetails = array(
					'creditcardtype' => 'Visa', 					// Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
					'acct' => '4716392328094345', 								// Required.  Credit card number.  No spaces or punctuation.
					'expdate' => '022020', 							// Required.  Credit card expiration date.  Format is MMYYYY
					'cvv2' => '456', 								// Requirements determined by your PayPal account settings.  Security digits for credit card.
					'startdate' => '', 							// Month and year that Maestro or Solo card was issued.  MMYYYY
					'issuenumber' => ''							// Issue number of Maestro or Solo card.  Two numeric digits max.
				);
				
$PayerInfo = array(
					'email' => 'sandbox@angelleye.com', 								// Email address of payer.
					'payerid' => '', 							// Unique PayPal customer ID for payer.
					'payerstatus' => '', 						// Status of payer.  Values are verified or unverified
					'business' => '' 							// Payer's business name.
				);
				
$PayerName = array(
					'salutation' => '', 						// Payer's salutation.  20 char max.
					'firstname' => 'Tester', 							// Payer's first name.  25 char max.
					'middlename' => '', 						// Payer's middle name.  25 char max.
					'lastname' => 'Testerson', 							// Payer's last name.  25 char max.
					'suffix' => ''								// Payer's suffix.  12 char max.
				);
				
$BillingAddress = array(
						'street' => '707 W. Bay Drive', 						// Required.  First street address.
						'street2' => '', 						// Second street address.
						'city' => 'Largo', 							// Required.  Name of City.
						'state' => 'FL', 							// Required. Name of State or Province.
						'countrycode' => 'US', 					// Required.  Country code.
						'zip' => '33770', 							// Required.  Postal code of payer.
						'phonenum' => '' 						// Phone Number of payer.  20 char max.
					);
					
$ShippingAddress = array(
						'shiptoname' => '', 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
						'shiptostreet' => '', 					// Required if shipping is included.  First street address.  100 char max.
						'shiptostreet2' => '', 					// Second street address.  100 char max.
						'shiptocity' => '', 					// Required if shipping is included.  Name of city.  40 char max.
						'shiptostate' => '', 					// Required if shipping is included.  Name of state or province.  40 char max.
						'shiptozip' => '', 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
						'shiptocountrycode' => '', 				// Required if shipping is included.  Country code of shipping address.  2 char max.
						'shiptophonenum' => ''					// Phone number for shipping address.  20 char max.
						);
					
$PaymentDetails = array(
						'amt' => '3.00', 							// Required.  Total amount of order, including shipping, handling, and tax.  
						'currencycode' => 'USD', 					// Required.  Three-letter currency code.  Default is USD.
						'itemamt' => '', 						// Required if you include itemized cart details. (L_AMTn, etc.)  Subtotal of items not including S&H, or tax.
						'shippingamt' => '', 					// Total shipping costs for the order.  If you specify shippingamt, you must also specify itemamt.
						'handlingamt' => '', 					// Total handling costs for the order.  If you specify handlingamt, you must also specify itemamt.
						'taxamt' => '', 						// Required if you specify itemized cart tax details. Sum of tax for all items on the order.  Total sales tax. 
						'desc' => 'Testing Payments Pro DESC Field', 							// Description of the order the customer is purchasing.  127 char max.
						'custom' => 'TEST', 						// Free-form field for your own use.  256 char max.
						'invnum' => 'ZZZ-123-ABC', 						// Your own invoice or tracking number
						'buttonsource' => '', 					// An ID code for use by 3rd party apps to identify transactions.
						'notifyurl' => ''						// URL for receiving Instant Payment Notifications.  This overrides what your profile is set to use.
					);

$OrderItems = array();		
$Item	 = array(
						'l_name' => 'Test Widget', 						// Item Name.  127 char max.
						'l_desc' => 'This is a test widget description.', 						// Item description.  127 char max.
						'l_amt' => '1.00', 							// Cost of individual item.
						'l_number' => 'ABC-123', 						// Item Number.  127 char max.
						'l_qty' => '1', 							// Item quantity.  Must be any positive integer.  
						'l_taxamt' => '', 						// Item's sales tax amount.
						'l_ebayitemnumber' => '', 				// eBay auction number of item.
						'l_ebayitemauctiontxnid' => '', 		// eBay transaction ID of purchased item.
						'l_ebayitemorderid' => '' 				// eBay order ID for the item.
				);
array_push($OrderItems, $Item);

$Item	 = array(
						'l_name' => 'Test Widget 2', 						// Item Name.  127 char max.
						'l_desc' => 'This is a test widget description.', 						// Item description.  127 char max.
						'l_amt' => '2.00', 							// Cost of individual item.
						'l_number' => 'ABC-XYZ', 						// Item Number.  127 char max.
						'l_qty' => '1', 							// Item quantity.  Must be any positive integer.  
						'l_taxamt' => '', 						// Item's sales tax amount.
						'l_ebayitemnumber' => '', 				// eBay auction number of item.
						'l_ebayitemauctiontxnid' => '', 		// eBay transaction ID of purchased item.
						'l_ebayitemorderid' => '' 				// eBay order ID for the item.
				);
array_push($OrderItems, $Item);

$PayPalRequestData = array(
						   'DPFields' => $DPFields, 
						   'CCDetails' => $CCDetails, 
						   'PayerInfo' => $PayerInfo,
						   'PayerName' => $PayerName, 
						   'BillingAddress' => $BillingAddress, 
						   'PaymentDetails' => $PaymentDetails, 
						   'OrderItems' => $OrderItems
						   );

$PayPalResult = $PayPal -> DoDirectPayment($PayPalRequestData);

$_SESSION['transaction_id'] = isset($PayPalResult['TRANSACTIONID']) ? $PayPalResult['TRANSACTIONID'] : '';

echo '<pre />';
print_r($PayPalResult);
