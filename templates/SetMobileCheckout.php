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
$SMCFields = array(
					'phonecountrycode' => '', 				// Three-digit country code for buyer's phone number.  
					'phonenum' => '', 						// Localized phone number used by the buyer to submit the payment request.  if the phone number is activated for mobile checkout, PayPal uses this value to pre-fill the PayPal login page.
					'amt' => '', 							// Required. Cost of item before tax and shipping.
					'currencycode' => '', 					// Required.  Three-character currency code.  Default is USD.
					'taxamt' => '', 						// Tax on item purchased.
					'shippingamt' => '', 					// shipping costs for this transaction.
					'desc' => '', 							// Required. The name of the item is being ordered.  127 char max.
					'number' => '', 						// Pass-through field allowing you to specify detailis, such as a SKU.  127 char max.
					'custom' => '', 						// Free-form field for your own use.  256 char max.
					'invnum' => '', 						// Your own invoice or tracking number.  127 char max.
					'returnurl' => '', 						// URL to direct the browser to after leaving PayPal pages.
					'cancelurl' => '', 						// URL to direct the borwser to if the user cancels payment.
					'addressdisplay' => '', 				// Indiciates whether or not a shipping address is required.  1 or 0. 
					'sharephonenum' => '', 					// Indiciates whether or not the customer's phone number is returned to the merchant.  1 or 0.  
					'email' => '' 							// Email address of the buyer as entered during checkout.  If the phone number is not activated for Mobile Checkout, PayPal uses this value to pre-fill the PayPal login page.  127 char max.
				);
				
$ShippingAddress = array(
						'shiptoname' => '', 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
						'shiptostreet' => '', 					// Required if shipping is included.  First street address.  100 char max.
						'shiptostreet2' => '', 					// Second street address.  100 char max.
						'shiptocity' => '', 					// Required if shipping is included.  Name of city.  40 char max.
						'shiptostate' => '', 					// Required if shipping is included.  Name of state or province.  40 char max.
						'shiptozip' => '', 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
						'shiptocountry' => '' 					// Required if shipping is included.  Country code of shipping address.  2 char max.
						);
						
$PayPalRequestData = array(
						'SMCFields' => $SMCFields, 
						'ShippingAddress' => $ShippingAddress
						);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->SetMobileCheckout($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>