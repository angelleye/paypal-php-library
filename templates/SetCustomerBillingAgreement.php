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
$SCBAFields = array(
					'returnurl' => '', 									// Required.  URL to which the customer's browser is returned after chooing to pay with PayPal.
					'cancelurl' => '', 									// Required.  URL to which the customer is returned if he does not approve the use of PayPal to pay you.
					'localcode' => '', 									// Local of pages displayed by PayPal during checkout.  
					'pagestyle' => '', 									// Sets the custom payment page style for payment pages associated with this button/link.
					'hdrimg' => '', 									// A URL for the image you want to appear at the top, left of the payment page.  Max size 750 x 90
					'hdrbordercolor' => '', 							// Sets the border color around the header of the payment page.
					'hdrbackcolor' => '', 								// Sets the background color for the header of the payments page.
					'payflowcolor' => '', 								// Sets the background color for the payment page.
					'email' => ''										// Email address of the buyer as entered during checkout.  Will be pre-filled if included.
					);	
					
$BillingAgreements = array();
$Item = array(
			  'l_billingtype' => '', 							// Required.  Type of billing agreement.  For recurring payments it must be RecurringPayments.  You can specify up to ten billing agreements.  For reference transactions, this field must be either:  MerchantInitiatedBilling, or MerchantInitiatedBillingSingleSource
			  'l_billingagreementdescription' => '', 			// Required for recurring payments.  Description of goods or services associated with the billing agreement.  
			  'l_paymenttype' => '', 							// Specifies the type of PayPal payment you require for the billing agreement.  Any or IntantOnly
			  'l_billingagreementcustom' => ''					// Custom annotation field for your own use.  256 char max.
			  );
array_push($BillingAgreements, $Item);

$PayPalRequestData = array(
						'SCBAFields' => $SCBAFields, 
						'BillingAgreements' => $BillingAgreements
						);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->SetCustomerBillingAgreement($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>