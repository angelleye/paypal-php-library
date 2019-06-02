<?php

/*
 *
 * This requires using Obtain User's Consent to fetch the refresh token of the third party merchant. 
 * Please look at  paypal-php-library\samples\rest\identity\GetUserConsentURL.php
 * Follow whole process and you will get refresh Token and put that in given variable and start the process.
 * 
 */

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

$refreshToken = "SCNWVZfdg43XaOmoEicazpkXyda32CGnP208EkuQ_QBIrXCYMhlvORFHHyoXPT0VbEMIHYVJEm0gVf1Vf72YgJzPScBenKoVPq__y1QRT7wwJo3WYADwUW4Q5ic";

$invoiceId = 'INV2-GALN-A78F-EFUB-YT74';    //Required. The ID of the invoice for which to show details.

$returnArray = $PayPal->GetThirdPartyInvoice($invoiceId,$refreshToken);
echo "<pre>";
print_r($returnArray);
