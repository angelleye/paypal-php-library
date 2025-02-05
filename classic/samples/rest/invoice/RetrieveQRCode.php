<?php
// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'Sandbox' => $sandbox,
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'LogResults' => $log_results, 
    'LogPath' => $log_path,
    'LogLevel' => $log_level  
);
$PayPal = new \angelleye\PayPal\rest\invoice\InvoiceAPI($configArray);

$parameters = array(
    'width'  => '300',                              // The width, in pixels, of the QR code image. Valid value is from 150 to 500. Default is 500.Default: 500.
    'height' => '300',                              // The height, in pixels, of the QR code image. Valid value is from 150 to 500. Default is 500.Default: 500.
    'action' => '',                                 // Default: pay. The type of URL for which to generate a QR code. Default is pay and is the only supported value.
);

$InvoiceID = 'INV2-JZ52-L8SK-U4L2-RF8M';            // Required. Specify the ID of the invoice to remind.

$path = 'images/sample.png';                        // Path to save Image.

$returnArray = $PayPal->RetrieveQRCode($parameters,$InvoiceID,$path);
echo "<pre>";
print_r($returnArray);
echo '<img src="data:image/png;base64,'. $returnArray['Image']. '" alt="Invoice QR Code" />';
