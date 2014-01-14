<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('../includes/config.php');
require_once('paypal.class.php');

// Configure PayPal object
$paypal_config = array('Sandbox' => $sandbox);
$paypal = new PayPal($paypal_config);

// Pull out data from PayPal request
$method = isset($_POST['METHOD']) ? $_POST['METHOD'] : '';
$token = isset($_POST['TOKEN']) ? $_POST['TOKEN'] : '';
$currency_code = isset($_POST['CURRENCYCODE']) ? $_POST['CURRENCYCODE'] : '';
$local_code = isset($_POST['LOCALECODE']) ? $_POST['LOCALECODE'] : '';

$order_items = $paypal->GetOrderItems($_POST);
$shipping_street = isset($_POST['SHIPTOSTREET']) ? $_POST['SHIPTOSTREET'] : '';
$shipping_street2 = isset($_POST['SHIPTOSTREET2']) ? $_POST['SHIPTOSTREET2'] : '';
$shipping_city = isset($_POST['SHIPTOCITY']) ? $_POST['SHIPTOCITY'] : '';
$shipping_state = isset($_POST['SHIPTOSTATE']) ? $_POST['SHIPTOSTATE'] : '';
$shipping_zip = isset($_POST['SHIPTOZIP']) ? $_POST['SHIPTOZIP'] : '';
$shipping_country_code = isset($_POST['SHIPTOCOUNTRY']) ? $_POST['SHIPTOCOUNTRY'] : '';

// Now we have all our data from PayPal so we can go ahead and calculate shipping
//
//
//

// Now we can generate a response for PayPal based on our new shipping values we got back from our carrier API.
$CBFields = array();

// Gather shipping options.  This part will probably go under the carrier API section and load a new option for each returned by the carrier.
$ShippingOptions = array();
$Option = array(
				'l_shippingoptionisdefault' => 'true', 						// Shipping option.  Required if specifying the Callback URL.  true or false.  Must be only 1 default!
				'l_shippingoptionname' => 'UPS', 							// Shipping option name.  Required if specifying the Callback URL.  50 character max.
				'l_shipingpoptionlabel' => 'UPS', 							// Shipping option label.  Required if specifying the Callback URL.  50 character max.
				'l_shippingoptionamount' => '5.00', 						// Shipping option amount.  Required if specifying the Callback URL.  
				'l_taxamt' => '0.00', 										// New tax amount based on this shipping option and address.
				'l_insuranceamount' => '1.00' 								// New insurance amount based on this shipping option and address.
				);
array_push($ShippingOptions, $Option);

$Option = array(
				'l_shippingoptionisdefault' => 'false', 						// Shipping option.  Required if specifying the Callback URL.  true or false.  Must be only 1 default!
				'l_shippingoptionname' => 'UPS', 							// Shipping option name.  Required if specifying the Callback URL.  50 character max.
				'l_shipingpoptionlabel' => 'UPS', 							// Shipping option label.  Required if specifying the Callback URL.  50 character max.
				'l_shippingoptionamount' => '20.00', 						// Shipping option amount.  Required if specifying the Callback URL.  
				'l_taxamt' => '0.00', 										// New tax amount based on this shipping option and address.
				'l_insuranceamount' => '1.00' 								// New insurance amount based on this shipping option and address.
				);
array_push($ShippingOptions, $Option);

$callback_data_request_array = array(
								'CBFields' => $CBFields, 
								'ShippingOptions' => $ShippingOptions
								);

$callback_data_response = $paypal->CallbackResponse($callback_data_request_array);

$request_content = '';
foreach($_POST as $var => $val)
	$request_content .= '&' . $var . '=' . urldecode($val);

$response_content_body = '';
$response_content = $paypal->NVPToArray($callback_data_response);
foreach($response_content as $var => $val)
	$response_content_body .= $var . ': ' . urldecode($val) . '<br />';
	
echo $callback_data_response;
?>