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

echo $_SESSION['paypal_token'];

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->GetExpressCheckoutDetails($_SESSION['paypal_token']);

if($PayPal->APICallSuccessful($PayPalResult['ACK']))
{
    $_SESSION['paypal_payer_id'] = !empty($_GET['PayerID']) ? $_GET['PayerID'] : '';
    echo '<a href="DoExpressCheckoutPayment.php">Continue to DoExpressCheckoutPayment.</a><br /><br />';
}
else
{
    $PayPal->DisplayErrors($PayPalResult['ERRORS']);
}

echo '<pre />';
print_r($PayPalResult);
