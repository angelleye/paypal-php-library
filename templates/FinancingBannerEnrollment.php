<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../includes/paypal.class.php');
require_once('../includes/paypal.financing.class.php');

// Create PayPal object.
$PayPalConfig = array(
					  'Sandbox' => $sandbox,
					  'AccessKey' => $finance_access_key, 
					  'ClientSecret' => $finance_client_secret
					);

$PayPal = new PayPal_Financing($PayPalConfig);

// Prepare request arrays
$BannerEnrollmentParams = array(
							'PayerID' => '', 			// ID of the invoice.
							'SellerName' => '', 			// Subject of the cancelation notification.
							'EmailAddress' => '' 		// Note to send the payer within the cancelation notification.
							);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->BannerEnrollment($BannerEnrollmentParams);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>