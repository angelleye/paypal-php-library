<?php
if(!session_id()) session_start();

require_once('../includes/config.php');
require_once('../autoload.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'Sandbox' => $sandbox,
    'PrintHeaders' => $print_headers, 
    'LogResults' => $log_results, 
    'LogPath' => $log_path
);

$PayPal = new angelleye\PayPal\PayPal_IntegratedSignup($configArray);

$PartnerID = 'F29HACJW4XYU4';   // your payer ID
$MerchantTrackingID = '12d29842a912a'; // the value you supplied for customer_data.partner_specific_identifiers.value in your Prefill API call.

$responseData = $PayPal->getPayerID($PartnerID,$MerchantTrackingID);

echo "<pre>";
echo "Hi";
print_r($responseData);
exit;


