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
$SetAccessPermissionsFields = array(
									'ReturnURL' => '', 														// URL to return the browser to after authorizing permissions.
									'CancelURL' => '', 													 	// URL to return if the customer cancels authorization
									'LogoutURL' => '', 														// URL to return to on logout from PayPal
									'LocalCode' => '', 														// Local of pages displayed by PayPal during authentication.  AU, DE, FR, IT, GB, ES, US
									'PageStyle' => '', 														// Sets the custom payment page style of the PayPal pages associated with this button/link.
									'HDRIMG' => '', 														// URL for the iamge you want to appear at the top of the PayPal pages.  750x90.  Should be stored on a secure server.  127 char max.
									'HDRBorderColor' => '', 												// Sets the border color around the header on PayPal pages.HTML Hexadecimal value.
									'HDRBackColor' => '', 													// Sets the background color for PayPal pages.
									'PayFlowColor' => '', 													// Sets the background color for the payment page.
									'InitFlowType' => '', 													// The initial flow type, which is one of the following:  login  / signup   Default is login.
									'FirstName' => '', 														// Customer's first name.
									'LastName' => ''
									);

$RequiredPermissions = array(
							 'Email', 
							 'Name', 
							 'GetBalance', 
							 'RefundTransaction', 
							 'GetTransactionDetails', 
							 'TransactionSearch', 
							 'MassPay', 
							 'EncryptedWebsitePayments', 
							 'GetExpressCheckoutDetails', 
							 'SetExpressCheckout', 
							 'DoExpressCheckoutPayment', 
							 'DoCapture', 
							 'DoAuthorization', 
							 'DoReauthorization', 
							 'DoVoid', 
							 'DoDirectPayment', 
							 'SetMobileCheckout', 
							 'CreateMobileCheckout', 
							 'DoMobileCheckoutPayment', 
							 'DoUATPAuthorization', 
							 'DoUATPExpressCheckoutPayment', 
							 'GetBillingAgreementCustomerDetails', 
							 'SetCustomerBillingAgreement', 
							 'CreateBillingAgreement', 
							 'BillAgreementUpdate', 
							 'BillUser', 
							 'DoReferenceTransaction', 
							 'Express_Checkout', 
							 'Admin_API', 
							 'Auth_Settle', 
							 'Transaction_History'
							 );

$OptionalPermissions = array(
							 'Email', 
							 'Name', 
							 'GetBalance', 
							 'RefundTransaction', 
							 'GetTransactionDetails', 
							 'TransactionSearch', 
							 'MassPay', 
							 'EncryptedWebsitePayments', 
							 'GetExpressCheckoutDetails', 
							 'SetExpressCheckout', 
							 'DoExpressCheckoutPayment', 
							 'DoCapture', 
							 'DoAuthorization', 
							 'DoReauthorization', 
							 'DoVoid', 
							 'DoDirectPayment', 
							 'SetMobileCheckout', 
							 'CreateMobileCheckout', 
							 'DoMobileCheckoutPayment', 
							 'DoUATPAuthorization', 
							 'DoUATPExpressCheckoutPayment', 
							 'GetBillingAgreementCustomerDetails', 
							 'SetCustomerBillingAgreement', 
							 'CreateBillingAgreement', 
							 'BillAgreementUpdate', 
							 'BillUser', 
							 'DoReferenceTransaction', 
							 'Express_Checkout', 
							 'Admin_API', 
							 'Auth_Settle', 
							 'Transaction_History'
							 );

$PayPalRequestData = array(
						   'SetAccessPermissionsFields' => $SetAccessPermissionsFields, 
						   'RequiredPermissions' => $RequiredPermissions, 
						   'OptionalPermissions' => $OptionalPermissions
						   );
						   
$PayPalRequestData = array(
						'SetAccessPermissionsFields' => $SetAccessPermissionsFields, 
						'RequiredPermissions' => $RequiredPermissions, 
						'OptionalPermissions' => $OptionalPermissions 
						);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->SetAccessPermissions($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>