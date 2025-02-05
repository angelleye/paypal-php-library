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

$PayPal = new \angelleye\PayPal\rest\identity\IdentityAPI($configArray);
// To obtain User Info, you have to follow three steps in general.
// First, you need to obtain user's consent to retrieve the information you want.
// This is explained in the example "GetUserConsentURL.php".

// Once you get the user's consent, the end result would be long lived refresh token.
// This refresh token should be stored in a permanent storage for later use.

// You can retrieve the refresh token by executing GetUserConsentURL.php and store the refresh token

$refreshToken= '';

$requestData = array(
    'refreshToken' => $refreshToken,    
);

$returnArray = $PayPal->GetUserInfo($requestData);
echo "<pre>";
print_r($returnArray);
echo "</pre>";