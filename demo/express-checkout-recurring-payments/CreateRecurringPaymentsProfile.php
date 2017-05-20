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
                    'LogPath' => $log_path,
		);

$PayPal = new angelleye\PayPal\PayPal($PayPalConfig);

$CRPPFields = array(
		'token' => $_SESSION['paypal_token'], 								// Token returned from PayPal SetExpressCheckout.
		);
				
$DaysTimestamp = strtotime('now');
$Mo = date('m', $DaysTimestamp);
$Day = date('d', $DaysTimestamp);
$Year = date('Y', $DaysTimestamp);
$StartDateGMT = $Year . '-' . $Mo . '-' . $Day . 'T00:00:00\Z';
				
$ProfileDetails = array(
                    'subscribername' => $_SESSION['first_name'].' '.$_SESSION['last_name'], 					// Full name of the person receiving the product or service paid for by the recurring payment.  32 char max.
                    'subscribername' => '',
                    'profilestartdate' => $StartDateGMT, 					// Required.  The date when the billing for this profile begins.  Must be a valid date in UTC/GMT format.
                    'profilereference' => '' 					// The merchant's own unique invoice number or reference ID.  127 char max.
				);
				
$ScheduleDetails = array(
					'desc' => 'DescForRP', 								// Required.  Description of the recurring payment.  This field must match the corresponding billing agreement description included in SetExpressCheckout.
					'maxfailedpayments' => '3', 					// The number of scheduled payment periods that can fail before the profile is automatically suspended.  
					'autobillamt' => 'AddToNextBilling' 						// This field indicates whether you would like PayPal to automatically bill the outstanding balance amount in the next billing cycle.  Values can be: NoAutoBill or AddToNextBilling
				);
				
$BillingPeriod = array(
					'trialbillingperiod' => '', 
					'trialbillingfrequency' => '', 
					'trialtotalbillingcycles' => '', 
					'trialamt' => '', 
					'billingperiod' => $_SESSION['billingperiod'], 						// Required.  Unit for billing during this subscription period.  One of the following: Day, Week, SemiMonth, Month, Year
					'billingfrequency' => $_SESSION['billingfrequency'], 					// Required.  Number of billing periods that make up one billing cycle.  The combination of billing freq. and billing period must be less than or equal to one year. 
					'totalbillingcycles' => $_SESSION['totalbillingcycles'], 				// the number of billing cycles for the payment period (regular or trial).  For trial period it must be greater than 0.  For regular payments 0 means indefinite...until canceled.  
					'amt' => $_SESSION['items'][1]['amt'], 								// Required.  Billing amount for each billing cycle during the payment period.  This does not include shipping and tax. 
					'currencycode' => 'USD', 						// Required.  Three-letter currency code.
					'shippingamt' => $_SESSION['shopping_cart']['shipping'], 						// Shipping amount for each billing cycle during the payment period.
					'taxamt' => $_SESSION['shopping_cart']['tax'] 								// Tax amount for each billing cycle during the payment period.
				);
				
$ActivationDetails = array(
					'initamt' => $_SESSION['items'][0]['amt'], 							// Initial non-recurring payment amount due immediatly upon profile creation.  Use an initial amount for enrolment or set-up fees.
					'failedinitamtaction' => '', 				// By default, PayPal will suspend the pending profile in the event that the initial payment fails.  You can override this.  Values are: ContinueOnFailure or CancelOnFailure
				);
				
$PayerInfo = array(
					'email' => $_SESSION['email'], 								// Email address of payer.
					'payerid' => $_SESSION['paypal_payer_id'], 							// Unique PayPal customer ID for payer.
					'payerstatus' => '', 						// Status of payer.  Values are verified or unverified
					'countrycode' => '', 						// Payer's country of residence in the form of the two letter code.
					'business' => '' 							// Payer's business name.
				);
				
$PayerName = array(
					'salutation' => '', 						// Payer's salutation.  20 char max.
					'firstname' => $_SESSION['first_name'], 							// Payer's first name.  25 char max.
					'middlename' => '', 						// Payer's middle name.  25 char max.
					'lastname' => $_SESSION['last_name'], 							// Payer's last name.  25 char max.
					'suffix' => ''								// Payer's suffix.  12 char max.
				);				
					
$ShippingAddress = array(
						'shiptoname' => $_SESSION['shipping_name'], 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
						'shiptostreet' => $_SESSION['shipping_street'], 					// Required if shipping is included.  First street address.  100 char max.
						'shiptostreet2' => '', 					// Second street address.  100 char max.
						'shiptocity' => $_SESSION['shipping_city'], 					// Required if shipping is included.  Name of city.  40 char max.
						'shiptostate' => $_SESSION['shipping_state'], 					// Required if shipping is included.  Name of state or province.  40 char max.
						'shiptozip' => $_SESSION['shipping_zip'], 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
						'shiptocountrycode' => $_SESSION['shipping_country_code'], 				// Required if shipping is included.  Country code of shipping address.  2 char max.
						'shiptophonenum' => $_SESSION['phone_number']					// Phone number for shipping address.  20 char max.
						);
						
$PayPalRequestData = array(
'ProfileDetails' => $ProfileDetails, 
'ScheduleDetails' => $ScheduleDetails, 
'BillingPeriod' => $BillingPeriod, 
'PayerInfo' => $PayerInfo, 
'PayerName' => $PayerName, 
'CRPPFields' => $CRPPFields,
'ActivationDetails' => $ActivationDetails,
'ShippingAddress' => $ShippingAddress    
);

$PayPalResult = $PayPal->CreateRecurringPaymentsProfile($PayPalRequestData);

if($PayPal->APICallSuccessful($PayPalResult['ACK'])){
    $_SESSION['RecurringProfileId'] = $PayPalResult['PROFILEID'];
    header('Location: order-complete.php');
}
else{
    $_SESSION['paypal_errors'] = $PayPalResult['ERRORS'];
    header('Location: ../error.php');
}

?>