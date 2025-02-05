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

$PayPal = new \angelleye\PayPal\rest\notifications\NotificationsAPI($configArray);

$headers =array(
    'auth_algo' => 'SHA256withRSA',          // The algorithm that PayPal uses to generate the signature and that you can use to verify the signature. Extract this value from the PAYPAL-AUTH-ALGO response header, which is received with the webhook notification.
    'cert_url' => 'https://api.sandbox.paypal.com/v1/notifications/certs/CERT-360caa42-fca2a594-1d93a270',           // The X.509 public key certificate. Download the certificate from this URL and use it to verify the signature. Extract this value from the PAYPAL-CERT-URL response header, which is received with the webhook notification.
    'transmission_id' => '28559930-7bbb-11e9-9556-0b8753ae570e',    // The ID of the HTTP transmission. Contained in the PAYPAL-TRANSMISSION-ID header of the notification message.
    'transmission_sig' => 'UyGFLQPLgB/ucPt8pRXq9+69NKA8X9x7BNJCNgM9ei2zyPnPgE/higerPvpmD/J/VWFtpcLFUPe3WPh9X5Amr4uBbFR3mG1rR8E4jK43adAk9+Pcx9CAazykMwJ/VZYsUMgyGPMhRUofbrtI7sLclh+OULCFMz23wd04TJItYS/n+slkOfmUtqc4+5RVzNqsA1HYaKwZ3eZi6pRU2WYzsg/CltPLFenqCek6wHAIVOdH0HIBCqUtj2XQjtRBPzDZ6FZtCLnqpZ7MlSRCu0a0eJ7eySb0okDfmdTyfLmgsVdkSSTnNivYMU1f4rc3zUrCcFKSXBeSjHN1sZj5v3sJuw==',
       // The PayPal-generated asymmetric signature. Appears in the PAYPAL-TRANSMISSION-SIG header of the notification message.
    'transmission_time' => '2019-05-21T11:25:37Z',  // The date and time of the HTTP transmission, in Internet date and time format. Appears in the PAYPAL-TRANSMISSION-TIME header of the notification message.    
);

$webhook_id = '43023250MX034900R';               // The ID of the webhook as configured in your Developer Portal account.
$webhook_event = file_get_contents('php://input');            // A webhook event notification.

$returnArray = $PayPal->VerifyWebhookSignature($headers, $webhook_id,$webhook_content);

echo "<pre>";
print_r($returnArray);