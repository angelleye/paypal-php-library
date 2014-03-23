<?php
/**
 * 	Angell EYE PayPal Express Checkout Callback Service
 *	An open source PHP library written to easily work with PayPal's API's
 *	
 *  Copyright © 2014  Andrew K. Angell
 *	Email:  andrew@angelleye.com
 *  Facebook: angelleyeconsulting
 *  Twitter: angelleye
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 * @package			Angell_EYE_PayPal_Class_Library
 * @author			Andrew K. Angell
 * @copyright       Copyright © 2014 Angell EYE, LLC
 * @link			https://github.com/angelleye/PayPal-PHP-Library
 * @website			http://www.angelleye.com
 * @since			Version 1.52
 * @updated			01.14.2014
 * @filesource
*/

/*
 * This is a bare-bones template for building a callback listener for PayPal Express Checkout.
 * If you include the CALLBACK parameters in your SetExpressCheckout request, this is what you 
 * you would setup as your callback URL.  PayPal will hit your callback URL with address and item details 
 * from the Express Checkout review page in order to populate their screen with shipping and sales tax 
 * information that you pass back from this service.
 * 
 * This allows you to build real-time shipping and tax calculations into Express Checkout
 * so that you won't need to display another review page on your own site.   
*/
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once('../includes/config.php');
require_once('../autoload.php');

// Configure PayPal object
$paypal_config = array('Sandbox' => $sandbox);
$paypal = new PayPal\PayPal($paypal_config);

// Receive data from PayPal and load varaibles accordingly.
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

// Here, we may setup static shipping and tax options, or we could hit a 3rd party 
// web service API (eg. UPS, FedEx, USPS) to gather rates in real-time.  
//
//
//

// Now we can generate a response for PayPal based on our new shipping values we got back from our carrier API.
$CBFields = array();

// Gather shipping options.  If you're pulling rates from a carrier API you would be looping through 
// their response in order to populate $ShippingOptions.  Here, we're doing it manually for sample purposes.
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

// Now we pass the data into the class library which will return an NVP string
$callback_data_response = $paypal->CallbackResponse($callback_data_request_array);

// Gather the request data that PayPal sent us in case we need to log it somehow to see what's available.
$request_content = '';
foreach($_POST as $var => $val)
{
	$request_content .= '&' . $var . '=' . urldecode($val);
}

// Pass the shipping/tax data into the library to obtain an NVP string that we'll 
// simply output as a web service response back to PayPal.
$response_content_body = '';
$response_content = $paypal->NVPToArray($callback_data_response);
foreach($response_content as $var => $val)
{
	$response_content_body .= $var . ': ' . urldecode($val) . '<br />';
}

echo $callback_data_response;
?>