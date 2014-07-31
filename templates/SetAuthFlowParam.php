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
$SetAuthFlowParamFields = array(
								'ReturnURL' => '', 														// URL to which the customer's browser is returned after choosing to authenticate with PayPal
								'CancelURL' => '', 														// URL to which the customer is returned if they decide not to log in.
								'LogoutURL' => '', 														// URL to which the customer is returned after logging out from your site.
								'LocalCode' => '', 														// Local of pages displayed by PayPal during authentication.  AU, DE, FR, IT, GB, ES, US
								'PageStyle' => '', 														// Sets the custom payment page style of the PayPal pages associated with this button/link.
								'HDRIMG' => '', 														// URL for the iamge you want to appear at the top of the PayPal pages.  750x90.  Should be stored on a secure server.  127 char max.
								'HDRBorderColor' => '', 												// Sets the border color around the header on PayPal pages.HTML Hexadecimal value.
								'HDRBackColor' => '', 													// Sets the background color for PayPal pages.
								'PayFlowColor' => '', 													// Sets the background color for the payment page.
								'InitFlowType' => '', 													// The initial flow type, which is one of the following:  login  / signup   Default is login.
								'FirstName' => '', 														// Customer's first name.
								'LastName' => '',  														// Customer's last name.
								'ServiceName1' => 'Name', 
								'ServiceName2' => 'Email', 
								'ServiceDefReq1' => 'Required', 
								'ServiceDefReq2' => 'Required'
								);

$ShippingAddress = array(
						'ShipToName' => '', 													// Persona's name associated with this address.
						'ShipToStreet' => '', 													// First street address.
						'ShipToStreet2' => '', 													// Second street address.
						'ShipToCity' => '', 													// Name of city.
						'ShipToState' => '', 													// Name of State or Province.
						'ShipToZip' => '', 														// US Zip code or other country-specific postal code.
						'ShipToCountryCode' => '' 												// Country code.
						 );
						 
$PayPalRequestData = array(
						'SetAuthFlowParamFields' => $SetAuthFlowParamFields, 
						'ShippingAddress' => $ShippingAddress
						);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->SetAuthFlowParam($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>