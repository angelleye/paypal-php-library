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
    'auth_algo' => '',          // The algorithm that PayPal uses to generate the signature and that you can use to verify the signature. Extract this value from the PAYPAL-AUTH-ALGO response header, which is received with the webhook notification.
    'cert_url' => '',           // The X.509 public key certificate. Download the certificate from this URL and use it to verify the signature. Extract this value from the PAYPAL-CERT-URL response header, which is received with the webhook notification.
    'transmission_id' => '',    // The ID of the HTTP transmission. Contained in the PAYPAL-TRANSMISSION-ID header of the notification message.
    'transmission_sig' => '',   // The PayPal-generated asymmetric signature. Appears in the PAYPAL-TRANSMISSION-SIG header of the notification message.
    'transmission_time' => '',  // The date and time of the HTTP transmission, in Internet date and time format. Appears in the PAYPAL-TRANSMISSION-TIME header of the notification message.    
);

$webhook_id = '';               // The ID of the webhook as configured in your Developer Portal account.
$webhook_event = file_get_contents('php://input');            // A webhook event notification.

$returnArray = $PayPal->VerifyWebhookSignature($headers, $webhook_id,$webhook_content);

echo "<pre>";
print_r($returnArray);