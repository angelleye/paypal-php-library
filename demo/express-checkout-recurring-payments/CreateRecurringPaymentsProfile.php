<?php
/**
 * Include our config file and the PayPal library.
 */
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

/**
 * Here we're passing in the PayPal token we obtained
 * in the last step, GetExpressCheckoutDetails.
 */
$CRPPFields = array(
    'token' => $_SESSION['paypal_token'], 								// Token returned from PayPal SetExpressCheckout.
);

/**
 * Generate start date timestamp for the recurring profile.
 */
$DaysTimestamp = strtotime('now');
$Mo = date('m', $DaysTimestamp);
$Day = date('d', $DaysTimestamp);
$Year = date('Y', $DaysTimestamp);
$StartDateGMT = $Year . '-' . $Mo . '-' . $Day . 'T00:00:00\Z';

/**
 * Here we begin setting up the parameters for the CreateRecurringPaymentsProfile
 * API request.
 *
 * The template provided at /vendor/angelleye/paypal-php-library/templates/CreateRecurringPaymentsProfile.php
 * contains a lot more parameters that we aren't using here, so I've removed them to keep this clean.
 *
 */
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
					'shippingamt' => $_SESSION['shopping_cart']['shipping'], 						// Shipping amount for each billing cycle during the payment period.
					'taxamt' => $_SESSION['shopping_cart']['tax'] 								// Tax amount for each billing cycle during the payment period.
				);
				

$PayerInfo = array(
					'email' => $_SESSION['email'], 								// Email address of payer.
					'payerid' => $_SESSION['paypal_payer_id'], 							// Unique PayPal customer ID for payer.
				);
				
$PayerName = array(
					'firstname' => $_SESSION['first_name'], 							// Payer's first name.  25 char max.
					'lastname' => $_SESSION['last_name'], 							// Payer's last name.  25 char max.
				);

/**
 * Now we gather all of the parameters/arrays into a single nested array
 * that we pass into the library as a whole.
 */
$PayPalRequestData = array(
    'ProfileDetails' => $ProfileDetails,
    'ScheduleDetails' => $ScheduleDetails,
    'BillingPeriod' => $BillingPeriod,
    'PayerInfo' => $PayerInfo,
    'PayerName' => $PayerName,
    'CRPPFields' => $CRPPFields,
);

/**
 * Here we are making the call to the CreateRecurringPaymentsProfile function in the library,
 * and we're passing in our $PayPalRequestData that we just set above.
 */
$PayPalResult = $PayPal->CreateRecurringPaymentsProfile($PayPalRequestData);

/**
 * Now we'll check for any errors returned by PayPal, and if we get an error,
 * we'll save the error details to a session and redirect the user to an
 * error page to display it accordingly.
 *
 * If all goes well, we save our PayPal subscription profile ID
 * in a session so we can display on the order-complete page that
 * we'll redirect to.
 */
if($PayPal->APICallSuccessful($PayPalResult['ACK'])){
    $_SESSION['RecurringProfileId'] = $PayPalResult['PROFILEID'];
    header('Location: order-complete.php');
}
else{
    $_SESSION['paypal_errors'] = $PayPalResult['ERRORS'];
    header('Location: ../error.php');
}