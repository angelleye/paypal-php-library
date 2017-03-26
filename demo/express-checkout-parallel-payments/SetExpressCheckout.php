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
 * Here we are setting up the parameters for a basic Express Checkout flow.
 *
 * The template provided at /vendor/angelleye/paypal-php-library/templates/SetExpressCheckout.php
 * contains a lot more parameters that we aren't using here, so I've removed them to keep this clean.
 *
 * $domain used here is set in the config file.
 */
$SECFields = array(
					'maxamt' => round($_SESSION['shopping_cart']['grand_total'] * 2,2), 					// The expected maximum total amount the order will be, including S&H and sales tax.
					'returnurl' => $domain . 'demo/express-checkout-parallel-payments/GetExpressCheckoutDetails.php', 							    // Required.  URL to which the customer will be returned after returning from PayPal.  2048 char max.
					'cancelurl' => $domain . 'demo/express-checkout-parallel-payments/', 							    // Required.  URL to which the customer will be returned if they cancel payment on PayPal's site.
					'hdrimg' => 'https://www.angelleye.com/images/angelleye-paypal-header-750x90.jpg', 			// URL for the image displayed as the header during checkout.  Max size of 750x90.  Should be stored on an https:// server or you'll get a warning message in the browser.
					'logoimg' => 'https://www.angelleye.com/images/angelleye-logo-190x60.jpg', 					// A URL to your logo image.  Formats:  .gif, .jpg, .png.  190x60.  PayPal places your logo image at the top of the cart review area.  This logo needs to be stored on a https:// server.
					'brandname' => 'Angell EYE'							                                // A label that overrides the business name in the PayPal account on the PayPal hosted checkout pages.  127 char max.
				);

/**
 * Now we begin setting up our payment(s).
 *
 * Express Checkout includes the ability to setup parallel payments,
 * so we have to populate our $Payments array here accordingly.
 *
 * For this sample (and in most use cases) we only need a single payment,
 * but we still have to populate $Payments with a single $Payment array.
 *
 * Once again, the template file includes a lot more available parameters,
 * but for this basic sample we've removed everything that we're not using,
 * so all we have is an amount.
 */
$Payments = array();
$Payment = array(
    'amt' => $_SESSION['items'][0]['price'] * $_SESSION['items'][0]['qty'] , 	// Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
    'currencycode' => 'USD',
    'itemamt' => $_SESSION['items'][0]['price'] * $_SESSION['items'][0]['qty'],       // Subtotal of items only.			                                // A three-character currency code.  Default is USD.
    'shippingamt' => 0, 	// Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
    'handlingamt' => 0, 	// Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
    'taxamt' => 0, 			// Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order.
    'paymentaction' => 'Sale',
    'sellerpaypalaccountid' => $_SESSION['seller_a'],			// A unique identifier for the merchant.  For parallel payments, this field is required and must contain the Payer ID or the email address of the merchant.
    'paymentrequestid' => 'CART26488-PAYMENT0'
);
/**
 * Here we push our first item $Payment into our $Payments array for seller_a.
 */
array_push($Payments, $Payment);


$Payment = array(
    'amt' => $_SESSION['items'][1]['price'] * $_SESSION['items'][1]['qty'] ,    // Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
    'currencycode' => 'USD',
    'itemamt' => $_SESSION['items'][1]['price'] * $_SESSION['items'][1]['qty'],       // Subtotal of items only.                                            // A three-character currency code.  Default is USD.
    'shippingamt' => 0,     // Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
    'handlingamt' => 0,     // Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
    'taxamt' => 0,          // Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order.
    'paymentaction' => 'Sale',
    'sellerpaypalaccountid' => $_SESSION['seller_b'],         // A unique identifier for the merchant.  For parallel payments, this field is required and must contain the Payer ID or the email address of the merchant.
    'paymentrequestid' => 'CART26488-PAYMENT1'
);
/**
 * Here we push our second item $Payment into our $Payments array for seller_b.
 */
array_push($Payments, $Payment);


/**
 * Now we gather all of the arrays above into a single array.
 */
$PayPalRequestData = array(
					   'SECFields' => $SECFields, 
					   'Payments' => $Payments,
					   );

/**
 * Here we are making the call to the SetExpressCheckout function in the library,
 * and we're passing in our $PayPalRequestData that we just set above.
 */
$PayPalResult = $PayPal->SetExpressCheckout($PayPalRequestData);

/**
 * Now we'll check for any errors returned by PayPal, and if we get an error,
 * we'll save the error details to a session and redirect the user to an 
 * error page to display it accordingly.
 *
 * If all goes well, we save our token in a session variable so that it's
 * readily available for us later, and then redirect the user to PayPal
 * using the REDIRECTURL returned by the SetExpressCheckout() function.
 */
if($PayPal->APICallSuccessful($PayPalResult['ACK']))
{
    $_SESSION['paypal_token'] = isset($PayPalResult['TOKEN']) ? $PayPalResult['TOKEN'] : '';
    header('Location: ' . $PayPalResult['REDIRECTURL']);
}
else
{
    $_SESSION['paypal_errors'] = $PayPalResult['ERRORS'];
    header('Location: ../error.php');
}