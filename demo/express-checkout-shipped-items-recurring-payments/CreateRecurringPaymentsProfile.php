<?php
/**
 * Include our config file and the PayPal library.
 */
require_once('../../includes/config.php');
require_once('../../autoload.php');

/**
 * Setup configuration for the PayPal library using vars from the config file.
 * Then load the PayPal object into $PayPal
 */
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

/**
 * Now we'll setup the request params for CreateRecurringPaymentsProfile request,
 * which creates our subscription profile in PayPal.  We'll be using the same
 * token that we used in DoExpressCheckoutPayment.
 *
 *
 * Once again, the template for CreateRecurringPaymentsProfile provides
 * many more params that are available, but we've stripped everything
 * we are not using in this basic demo out.
 */
$CRPPFields = array(
		'token' => $_SESSION['paypal_token'], 								// Token returned from PayPal SetExpressCheckout.
		);

/**
 * Generate profile start time / timestamp
 */
$DaysTimestamp = strtotime('now');
$Mo = date('m', $DaysTimestamp);
$Day = date('d', $DaysTimestamp);
$Year = date('Y', $DaysTimestamp);
$StartDateGMT = $Year . '-' . $Mo . '-' . $Day . 'T00:00:00\Z';
				
$ProfileDetails = array(
                    'subscribername' => $_SESSION['first_name'].' '.$_SESSION['last_name'], 					// Full name of the person receiving the product or service paid for by the recurring payment.  32 char max.                    
                    'profilestartdate' => $StartDateGMT, 					// Required.  The date when the billing for this profile begins.  Must be a valid date in UTC/GMT format.
				);
				
$ScheduleDetails = array(
					'desc' => $_SESSION['shopping_cart']['subscription']['name'], 								// Required.  Description of the recurring payment.  This field must match the corresponding billing agreement description included in SetExpressCheckout.
					'maxfailedpayments' => '3', 					// The number of scheduled payment periods that can fail before the profile is automatically suspended.  
					'autobillamt' => 'AddToNextBilling' 						// This field indicates whether you would like PayPal to automatically bill the outstanding balance amount in the next billing cycle.  Values can be: NoAutoBill or AddToNextBilling
				);
				
$BillingPeriod = array(
					'billingperiod' => $_SESSION['shopping_cart']['subscription']['billing_period'], 						// Required.  Unit for billing during this subscription period.  One of the following: Day, Week, SemiMonth, Month, Year
					'billingfrequency' => $_SESSION['shopping_cart']['subscription']['billing_frequency'], 					// Required.  Number of billing periods that make up one billing cycle.  The combination of billing freq. and billing period must be less than or equal to one year.
					'totalbillingcycles' => $_SESSION['shopping_cart']['subscription']['total_billing_cycles'], 				// the number of billing cycles for the payment period (regular or trial).  For trial period it must be greater than 0.  For regular payments 0 means indefinite...until canceled.
					'amt' => $_SESSION['shopping_cart']['subscription']['amount'], 								// Required.  Billing amount for each billing cycle during the payment period.  This does not include shipping and tax.
				);

$PayerInfo = array(
					'email' => $_SESSION['email'], 								// Email address of payer.
					'payerid' => $_SESSION['paypal_payer_id'], 							// Unique PayPal customer ID for payer.
				);
				
$PayerName = array(
					'firstname' => $_SESSION['first_name'], 							// Payer's first name.  25 char max.
					'lastname' => $_SESSION['last_name'], 							// Payer's last name.  25 char max.
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

/**
 * Now we gather all of the arrays above into a single array.
 */
$PayPalRequestData = array(
    'CRPPFields' => $CRPPFields,
    'ProfileDetails' => $ProfileDetails,
    'ScheduleDetails' => $ScheduleDetails,
    'BillingPeriod' => $BillingPeriod,
    'PayerInfo' => $PayerInfo,
    'PayerName' => $PayerName,
    'ShippingAddress' => $ShippingAddress,
);

/**
 * Here we are making the call to the CreateRecurringPaymentsProfile function in the library,
 * and we're passing in our $PayPalRequestData that we just set above.
 */
$PayPalResult = $PayPal->CreateRecurringPaymentsProfile($PayPalRequestData);

/**
 * Now we'll check for any errors returned by PayPal, and if we get an error,
 * we'll save the error details to a session, but instead of redirecting
 * to an error page we're still going to redirect to the order complete page
 * because the DoExpressCheckoutPayment call was made successfully and the
 * buyer needs to know that.  We'll simply add the error about the subscription
 * to this page.
 *
 * If the call is successful, we'll save some data we might want to use
 * later into session variables, and then redirect to our final
 * thank you / receipt page.
 */
if($PayPal->APICallSuccessful($PayPalResult['ACK'])){
    $_SESSION['RecurringProfileId'] = $PayPalResult['PROFILEID'];
    header('Location: order-complete.php');
}
else{
    $_SESSION['paypal_errors'] = $PayPalResult['ERRORS'];
    header('Location: ../order-complete.php');
}