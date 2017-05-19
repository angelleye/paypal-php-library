<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
                'ClientID' => $rest_client_id,
                'ClientSecret' => $rest_client_secret
                );

$PayPal = new \angelleye\PayPal\rest\identity\IdentityAPI($configArray);
// To obtain User Info, you have to follow three steps in general.
// First, you need to obtain user's consent to retrieve the information you want.
// This is explained in the example "GetUserConsentURL.php".

// Once you get the user's consent, the end result would be long lived refresh token.
// This refresh token should be stored in a permanent storage for later use.

// Lastly, when you need to retrieve the user information, you need to generate the short lived access token
// to retreive the information. The short lived access token can be retrieved using the example shown in
// "GenerateAccessTokenFromRefreshToken.php", or as shown below

// You can retrieve the refresh token by executing ObtainUserConsent.php and store the refresh token

$refreshToken= 'R23AAGyZu3XpUNPdyUstkkEtRp6tYwz7FOvoPURXUQT-RlX28bUr2BVcTvyA5RVgTskJm68MZoC75_W5Be8DCcdLNMeL1BW5eNxxJWFk1k39yZ54giLx-xUM4TpyOH5YmaPRAIbOPDpLKHVvFzlGA';

$requestData = array(
    'refreshToken' => $refreshToken,    
);

$returnArray = $PayPal->GetUserInfo($requestData);
echo "<pre>";
print_r($returnArray);
echo "</pre>";