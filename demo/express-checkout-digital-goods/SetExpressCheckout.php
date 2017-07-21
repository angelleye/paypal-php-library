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
 *
 * For Digital Goods, we know that we will not need to calculate
 * shipping.  As such, we will not need our additional review
 * page that we've been using in our other Express Checkout demos.
 * Therefore, we have added the "skipdetails" parameter here set to a 1
 * to enable it.
 *
 * This way, we can skip over the final review and go straight to
 * DoExpressCheckoutPayment, which you'll see by following the flow.
 */
$SECFields = array(
					'maxamt' => round($_SESSION['shopping_cart']['grand_total'] * 2,2), 					// The expected maximum total amount the order will be, including S&H and sales tax.
					'returnurl' => $domain . 'demo/express-checkout-digital-goods/GetExpressCheckoutDetails.php', 							    // Required.  URL to which the customer will be returned after returning from PayPal.  2048 char max.
					'cancelurl' => $domain . 'demo/express-checkout-digital-goods/cancel.php', 							    // Required.  URL to which the customer will be returned if they cancel payment on PayPal's site.
					'hdrimg' => 'https://www.angelleye.com/images/angelleye-paypal-header-750x90.jpg', 			// URL for the image displayed as the header during checkout.  Max size of 750x90.  Should be stored on an https:// server or you'll get a warning message in the browser.
					'logoimg' => 'https://www.angelleye.com/images/angelleye-logo-190x60.jpg', 					// A URL to your logo image.  Formats:  .gif, .jpg, .png.  190x60.  PayPal places your logo image at the top of the cart review area.  This logo needs to be stored on a https:// server.
					'brandname' => 'Angell EYE', 							                                // A label that overrides the business name in the PayPal account on the PayPal hosted checkout pages.  127 char max.
					'customerservicenumber' => '816-555-5555', 				                                // Merchant Customer Service number displayed on the PayPal Review page. 16 char max.
					'skipdetails' => '1', 																	// This is a custom field not included in the PayPal documentation.  It's used to specify whether you want to skip the GetExpressCheckoutDetails part of checkout or not.  See PayPal docs for more info.
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
 * We are adding the "noshipping" parameter here with a value of 1 
 * to activate it, which is required for digital goods, and informs
 * the PayPal system that no shipping will be required for this payment.
 *
 * Once again, the template file includes a lot more available parameters,
 * but for this line items sample we've removed everything that we're not using.
 */
$Payments = array();
$Payment = array(
    'amt' => $_SESSION['shopping_cart']['grand_total'], 	// Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
	'itemamt' => $_SESSION['shopping_cart']['subtotal'], 	// Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.
	'noshipping' => '1', 									// The value 1 indiciates that on the PayPal pages, no shipping address fields should be displayed.  Maybe 1 or 0.
);

/**
 * Here we'll begin creating our order items that belong to this $Payment in the request.
 * We will loop through the items in our shopping cart to add them each into our
 * $Payment.
 *
 * We have added the "itemcategory" parameter here to notify the PayPal system
 * that this payment is for digital goods.
 */
$PaymentOrderItems = array();
foreach($_SESSION['shopping_cart']['items'] as $cart_item)
{
	$Item = array(
		'name' => $cart_item['name'], 							// Item name. 127 char max.
		'amt' => $cart_item['price'], 							// Cost of item.
		'number' => $cart_item['id'], 							// Item number.  127 char max.
		'qty' => $cart_item['qty'], 							// Item qty on order.  Any positive integer.
		'itemcategory' => 'Digital', 							// One of the following values:  Digital, Physical
	);
	array_push($PaymentOrderItems, $Item);
}

/**
 * Now that $PaymentOrderItems is filled with all of our shopping cart items,
 * we'll add that to our $Payment array.
 */
$Payment['order_items'] = $PaymentOrderItems;

/**
 * Here we push our single $Payment into our $Payments array.
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
 * using the REDIRECTURLDIGITALGOODS returned by the SetExpressCheckout() function.
 *
 * This is an important difference between a regular Express Checkout
 * and a Digital Goods Express Checkout!  You need to make sure you use
 * the correct redirect URL to PayPal.
 */
if($PayPal->APICallSuccessful($PayPalResult['ACK']))
{
    $_SESSION['paypal_token'] = isset($PayPalResult['TOKEN']) ? $PayPalResult['TOKEN'] : '';
    header('Location: ' . $PayPalResult['REDIRECTURLDIGITALGOODS']);
}
else
{
    $_SESSION['paypal_errors'] = $PayPalResult['ERRORS'];
    header('Location: ../error.php');
}