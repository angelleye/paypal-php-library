<?php
// Include required library files.
require_once('../../includes/config.php');
require_once('../../autoload.php');

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
$DECPFields = array(
    'token' => $_SESSION['paypal_token'], 								// Required.  A timestamped token, the value of which was returned by a previous SetExpressCheckout call.
    'payerid' => $_SESSION['paypal_payer_id'], 							// Required.  Unique PayPal customer id of the payer.  Returned by GetExpressCheckoutDetails, or if you used SKIPDETAILS it's returned in the URL back to your RETURNURL.
    'returnfmfdetails' => '', 					// Flag to indicate whether you want the results returned by Fraud Management Filters or not.  1 or 0.
    'giftmessage' => '', 						// The gift message entered by the buyer on the PayPal Review page.  150 char max.
    'giftreceiptenable' => '', 					// Pass true if a gift receipt was selected by the buyer on the PayPal Review page. Otherwise pass false.
    'giftwrapname' => '', 						// The gift wrap name only if the gift option on the PayPal Review page was selected by the buyer.
    'giftwrapamount' => '', 					// The amount only if the gift option on the PayPal Review page was selected by the buyer.
    'buyermarketingemail' => '', 				// The buyer email address opted in by the buyer on the PayPal Review page.
    'skipbacreation' => '', 					// Merchant specified flag which indicates whether to create a billing agreement as part of DoExpressCheckout or not. This field is used for reference transactions during billing agreement creation. Merchants who offer a store account can control whether PayPal must create a billing agreement or if billing agreement creation should be skipped. Set the value of this field to true to skip the creation of a billing agreement ID.
    'usesessionpaymentdetails' => '', 			// Merchant specified flag which indicates whether to use the payment details information provided in SetExpressCheckoutDetails or in DoExpressCheckoutPayment. Possible values are true, false, 1, 0. If this is set to true or 1, the payment details information would be used from what was passed in SetExpressCheckoutDetails. Any change in the paymentdetails passed in DoExpressCheckoutPayment will be ignored if this field is set to true.
    'surveyquestion' => '', 					// The survey question on the PayPal Review page.  50 char max.
    'surveychoiceselected' => '',  				// The survey response selected by the buyer on the PayPal Review page.  15 char max.
    'allowedpaymentmethod' => '', 				// The payment method type. Specify the value InstantPaymentOnly.
    'msgsubid' => '',                           // Unique ID passed for each API request to help prevent duplicate payments.  This ID is passed directly back in the response.
);
						
$Payments = array();

/**
 * Payment #1
 */
$Payment = array(
    'amt' => '10.00', 							// Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
    'currencycode' => '', 					// A three-character currency code.  Default is USD.
    'itemamt' => '7.00', 						// Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.
    'shippingamt' => '3.00', 					// Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
    'shipdiscamt' => '', 					// Shipping discount for this order, specified as a negative number.
    'insuranceamt' => '', 					// Total shipping insurance costs for this order.
    'insuranceoptionoffered' => '', 		// If true, the insurance drop-down on the PayPal review page displays the string 'Yes' and the insurance amount.  If true, the total shipping insurance for this order must be a positive number.
    'handlingamt' => '', 					// Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
    'taxamt' => '', 						// Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order.
    'desc' => 'Payment #1', 							// Description of items on the order.  127 char max.
    'custom' => '', 						// Free-form field for your own use.  256 char max.
    'invnum' => '', 						// Your own invoice or tracking number.  127 char max.
    'notifyurl' => '', 						// URL for receiving Instant Payment Notifications
    'multishipping' => '', 					// The value 1 indicates that this payment is associated with multiple shipping addresses.
    'shiptoname' => '', 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
    'shiptostreet' => '', 					// Required if shipping is included.  First street address.  100 char max.
    'shiptostreet2' => '', 					// Second street address.  100 char max.
    'shiptocity' => '', 					// Required if shipping is included.  Name of city.  40 char max.
    'shiptostate' => '', 					// Required if shipping is included.  Name of state or province.  40 char max.
    'shiptozip' => '', 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
    'shiptocountrycode' => '', 				// Required if shipping is included.  Country code of shipping address.  2 char max.
    'shiptophonenum' => '',  				// Phone number for shipping address.  20 char max.
    'notetext' => '', 						// Note to the merchant.  255 char max.
    'allowedpaymentmethod' => '', 			// The payment method type.  Specify the value InstantPaymentOnly.
    'paymentaction' => 'Sale', 					// How you want to obtain the payment.  When implementing parallel payments, this field is required and must be set to Order.
    'paymentrequestid' => 'payment1',  				// A unique identifier of the specific payment request, which is required for parallel payments.
    'sellerpaypalaccountid' => 'paypal-facilitator@angelleye.com',			// A unique identifier for the merchant.  For parallel payments, this field is required and must contain the Payer ID or the email address of the merchant.
    'transactionid' => '',					// Transaction ID number of the transaction that was created.  You can specify up to 10 payments.
    'bucketcategorytype' => '',             // The category of a payment.  Values are:  1 - International shipping, 2 - Local delivery, 3 - BOPIS (Buy online pick-up in store), 4 - PUDO (Pick up drop off)
    'location_type' => '',                  // The type of merchant location.  Set this field if the items purchased will not be shipped, such as, BOPIS transactions.  Values are: 1 - Consumer, 2 - Store, for BOPIS transactions, 3 - PUDO
    'location_id' => '',                    // The Location ID specified by the merchant for BOPIS or PUDO transactions.
);

$PaymentOrderItems = array();
$Item = array(
    'name' => 'Payment #1 Widget', 								// Item name. 127 char max.
    'desc' => 'Payment #1 Widget', 								// Item description. 127 char max.
    'amt' => '7.00', 								// Cost of item.
    'number' => '', 							// Item number.  127 char max.
    'qty' => '1', 								// Item qty on order.  Any positive integer.
    'taxamt' => '', 							// Item sales tax
    'itemurl' => '', 							// URL for the item.
    'itemcategory' => '', 						// One of the following values:  Digital, Physical
    'itemweightvalue' => '', 					// The weight value of the item.
    'itemweightunit' => '', 					// The weight unit of the item.
    'itemheightvalue' => '', 					// The height value of the item.
    'itemheightunit' => '', 					// The height unit of the item.
    'itemwidthvalue' => '', 					// The width value of the item.
    'itemwidthunit' => '', 						// The width unit of the item.
    'itemlengthvalue' => '', 					// The length value of the item.
    'itemlengthunit' => '',  					// The length unit of the item.
    'ebayitemnumber' => '', 					// Auction item number.
    'ebayitemauctiontxnid' => '', 				// Auction transaction ID number.
    'ebayitemorderid' => '',  					// Auction order ID number.
    'ebayitemcartid' => ''						// The unique identifier provided by eBay for this order from the buyer. These parameters must be ordered sequentially beginning with 0 (for example L_EBAYITEMCARTID0, L_EBAYITEMCARTID1). Character length: 255 single-byte characters
);
array_push($PaymentOrderItems, $Item);
$Payment['order_items'] = $PaymentOrderItems;

array_push($Payments, $Payment);

/**
 * Payment #2
 */
$Payment = array(
    'amt' => '20.00', 							// Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
    'currencycode' => '', 					// A three-character currency code.  Default is USD.
    'itemamt' => '17.00', 						// Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.
    'shippingamt' => '3.00', 					// Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
    'shipdiscamt' => '', 					// Shipping discount for this order, specified as a negative number.
    'insuranceamt' => '', 					// Total shipping insurance costs for this order.
    'insuranceoptionoffered' => '', 		// If true, the insurance drop-down on the PayPal review page displays the string 'Yes' and the insurance amount.  If true, the total shipping insurance for this order must be a positive number.
    'handlingamt' => '', 					// Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
    'taxamt' => '', 						// Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order.
    'desc' => 'Payment #2', 							// Description of items on the order.  127 char max.
    'custom' => '', 						// Free-form field for your own use.  256 char max.
    'invnum' => '', 						// Your own invoice or tracking number.  127 char max.
    'notifyurl' => '', 						// URL for receiving Instant Payment Notifications
    'multishipping' => '', 					// The value 1 indicates that this payment is associated with multiple shipping addresses.
    'shiptoname' => '', 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
    'shiptostreet' => '', 					// Required if shipping is included.  First street address.  100 char max.
    'shiptostreet2' => '', 					// Second street address.  100 char max.
    'shiptocity' => '', 					// Required if shipping is included.  Name of city.  40 char max.
    'shiptostate' => '', 					// Required if shipping is included.  Name of state or province.  40 char max.
    'shiptozip' => '', 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
    'shiptocountrycode' => '', 				// Required if shipping is included.  Country code of shipping address.  2 char max.
    'shiptophonenum' => '',  				// Phone number for shipping address.  20 char max.
    'notetext' => '', 						// Note to the merchant.  255 char max.
    'allowedpaymentmethod' => '', 			// The payment method type.  Specify the value InstantPaymentOnly.
    'paymentaction' => 'Sale', 					// How you want to obtain the payment.  When implementing parallel payments, this field is required and must be set to Order.
    'paymentrequestid' => 'payment2',  				// A unique identifier of the specific payment request, which is required for parallel payments.
    'sellerpaypalaccountid' => 'sandbox-seller@angelleye.com',			// A unique identifier for the merchant.  For parallel payments, this field is required and must contain the Payer ID or the email address of the merchant.
    'transactionid' => '',					// Transaction ID number of the transaction that was created.  You can specify up to 10 payments.
    'bucketcategorytype' => '',             // The category of a payment.  Values are:  1 - International shipping, 2 - Local delivery, 3 - BOPIS (Buy online pick-up in store), 4 - PUDO (Pick up drop off)
    'location_type' => '',                  // The type of merchant location.  Set this field if the items purchased will not be shipped, such as, BOPIS transactions.  Values are: 1 - Consumer, 2 - Store, for BOPIS transactions, 3 - PUDO
    'location_id' => '',                    // The Location ID specified by the merchant for BOPIS or PUDO transactions.
);

$PaymentOrderItems = array();
$Item = array(
    'name' => 'Payment #2 Widget', 								// Item name. 127 char max.
    'desc' => 'Payment #2 Widget', 								// Item description. 127 char max.
    'amt' => '17.00', 								// Cost of item.
    'number' => '', 							// Item number.  127 char max.
    'qty' => '1', 								// Item qty on order.  Any positive integer.
    'taxamt' => '', 							// Item sales tax
    'itemurl' => '', 							// URL for the item.
    'itemcategory' => '', 						// One of the following values:  Digital, Physical
    'itemweightvalue' => '', 					// The weight value of the item.
    'itemweightunit' => '', 					// The weight unit of the item.
    'itemheightvalue' => '', 					// The height value of the item.
    'itemheightunit' => '', 					// The height unit of the item.
    'itemwidthvalue' => '', 					// The width value of the item.
    'itemwidthunit' => '', 						// The width unit of the item.
    'itemlengthvalue' => '', 					// The length value of the item.
    'itemlengthunit' => '',  					// The length unit of the item.
    'ebayitemnumber' => '', 					// Auction item number.
    'ebayitemauctiontxnid' => '', 				// Auction transaction ID number.
    'ebayitemorderid' => '',  					// Auction order ID number.
    'ebayitemcartid' => ''						// The unique identifier provided by eBay for this order from the buyer. These parameters must be ordered sequentially beginning with 0 (for example L_EBAYITEMCARTID0, L_EBAYITEMCARTID1). Character length: 255 single-byte characters
);
array_push($PaymentOrderItems, $Item);
$Payment['order_items'] = $PaymentOrderItems;

array_push($Payments, $Payment);

/**
 * Payment #3
 */
$Payment = array(
    'amt' => '40.00', 							// Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
    'currencycode' => '', 					// A three-character currency code.  Default is USD.
    'itemamt' => '37.00', 						// Required if you specify itemized L_AMT fields. Sum of cost of all items in this order.
    'shippingamt' => '3.00', 					// Total shipping costs for this order.  If you specify SHIPPINGAMT you mut also specify a value for ITEMAMT.
    'shipdiscamt' => '', 					// Shipping discount for this order, specified as a negative number.
    'insuranceamt' => '', 					// Total shipping insurance costs for this order.
    'insuranceoptionoffered' => '', 		// If true, the insurance drop-down on the PayPal review page displays the string 'Yes' and the insurance amount.  If true, the total shipping insurance for this order must be a positive number.
    'handlingamt' => '', 					// Total handling costs for this order.  If you specify HANDLINGAMT you mut also specify a value for ITEMAMT.
    'taxamt' => '', 						// Required if you specify itemized L_TAXAMT fields.  Sum of all tax items in this order.
    'desc' => 'Payment #3', 							// Description of items on the order.  127 char max.
    'custom' => '', 						// Free-form field for your own use.  256 char max.
    'invnum' => '', 						// Your own invoice or tracking number.  127 char max.
    'notifyurl' => '', 						// URL for receiving Instant Payment Notifications
    'multishipping' => '', 					// The value 1 indicates that this payment is associated with multiple shipping addresses.
    'shiptoname' => '', 					// Required if shipping is included.  Person's name associated with this address.  32 char max.
    'shiptostreet' => '', 					// Required if shipping is included.  First street address.  100 char max.
    'shiptostreet2' => '', 					// Second street address.  100 char max.
    'shiptocity' => '', 					// Required if shipping is included.  Name of city.  40 char max.
    'shiptostate' => '', 					// Required if shipping is included.  Name of state or province.  40 char max.
    'shiptozip' => '', 						// Required if shipping is included.  Postal code of shipping address.  20 char max.
    'shiptocountrycode' => '', 				// Required if shipping is included.  Country code of shipping address.  2 char max.
    'shiptophonenum' => '',  				// Phone number for shipping address.  20 char max.
    'notetext' => '', 						// Note to the merchant.  255 char max.
    'allowedpaymentmethod' => '', 			// The payment method type.  Specify the value InstantPaymentOnly.
    'paymentaction' => 'Sale', 					// How you want to obtain the payment.  When implementing parallel payments, this field is required and must be set to Order.
    'paymentrequestid' => 'payment3',  				// A unique identifier of the specific payment request, which is required for parallel payments.
    'sellerpaypalaccountid' => 'sandbox-seller@angelleye.com',			// A unique identifier for the merchant.  For parallel payments, this field is required and must contain the Payer ID or the email address of the merchant.
    'transactionid' => '',					// Transaction ID number of the transaction that was created.  You can specify up to 10 payments.
    'bucketcategorytype' => '',             // The category of a payment.  Values are:  1 - International shipping, 2 - Local delivery, 3 - BOPIS (Buy online pick-up in store), 4 - PUDO (Pick up drop off)
    'location_type' => '',                  // The type of merchant location.  Set this field if the items purchased will not be shipped, such as, BOPIS transactions.  Values are: 1 - Consumer, 2 - Store, for BOPIS transactions, 3 - PUDO
    'location_id' => '',                    // The Location ID specified by the merchant for BOPIS or PUDO transactions.
);

$PaymentOrderItems = array();
$Item = array(
    'name' => 'Payment #3 Widget', 								// Item name. 127 char max.
    'desc' => 'Payment #3 Widget', 								// Item description. 127 char max.
    'amt' => '37.00', 								// Cost of item.
    'number' => '', 							// Item number.  127 char max.
    'qty' => '1', 								// Item qty on order.  Any positive integer.
    'taxamt' => '', 							// Item sales tax
    'itemurl' => '', 							// URL for the item.
    'itemcategory' => '', 						// One of the following values:  Digital, Physical
    'itemweightvalue' => '', 					// The weight value of the item.
    'itemweightunit' => '', 					// The weight unit of the item.
    'itemheightvalue' => '', 					// The height value of the item.
    'itemheightunit' => '', 					// The height unit of the item.
    'itemwidthvalue' => '', 					// The width value of the item.
    'itemwidthunit' => '', 						// The width unit of the item.
    'itemlengthvalue' => '', 					// The length value of the item.
    'itemlengthunit' => '',  					// The length unit of the item.
    'ebayitemnumber' => '', 					// Auction item number.
    'ebayitemauctiontxnid' => '', 				// Auction transaction ID number.
    'ebayitemorderid' => '',  					// Auction order ID number.
    'ebayitemcartid' => ''						// The unique identifier provided by eBay for this order from the buyer. These parameters must be ordered sequentially beginning with 0 (for example L_EBAYITEMCARTID0, L_EBAYITEMCARTID1). Character length: 255 single-byte characters
);
array_push($PaymentOrderItems, $Item);
$Payment['order_items'] = $PaymentOrderItems;

array_push($Payments, $Payment);

$UserSelectedOptions = array(
							 'shippingcalculationmode' => '', 	// Describes how the options that were presented to the user were determined.  values are:  API - Callback   or   API - Flatrate.
							 'insuranceoptionselected' => '', 	// The Yes/No option that you chose for insurance.
							 'shippingoptionisdefault' => '', 	// Is true if the buyer chose the default shipping option.  
							 'shippingoptionamount' => '', 		// The shipping amount that was chosen by the buyer.
							 'shippingoptionname' => '', 		// Is true if the buyer chose the default shipping option...??  Maybe this is supposed to show the name..??
							 );
							 
$PayPalRequestData = array(
					   'DECPFields' => $DECPFields, 
					   'Payments' => $Payments, 
					   //'UserSelectedOptions' => $UserSelectedOptions
					   );

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->DoExpressCheckoutPayment($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
