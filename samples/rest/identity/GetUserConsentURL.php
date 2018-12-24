<?php
// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
                'ClientID' => $rest_client_id,
                'ClientSecret' => $rest_client_secret,
                'LogResults' => $log_results, 
                'LogPath' => $log_path,
                'LogLevel' => $log_level
                );

$PayPal = new \angelleye\PayPal\rest\identity\IdentityAPI($configArray);

$redirectUri = $domain.'samples/rest/identity/UserConsentRedirect.php?success=true';  // Uri on merchant website to where the user must be redirected to post paypal login

// @param array $scope The access privilges that you are requesting for from the user. Pass empty array for all scopes.
// If you want some basic information then you just need to include 'openid', 'profile', 'address', 'email', 'phone' in the scope.
// If you want to special permission from the user then you need to include paypalattributes,expresscheckout and invoicing  in the scope.

$Scope   = array('openid', 'profile', 'address', 'email', 'phone',
                'https://uri.paypal.com/services/paypalattributes',
                'https://uri.paypal.com/services/expresscheckout',
                'https://uri.paypal.com/services/invoicing'); 

$requestData = array(
    'redirectUri' => $redirectUri,
    'scope'       => $Scope,
);
        
$returnArray = $PayPal->GetUserConsentURL($requestData);

echo "<pre>";
print_r($returnArray);
echo "</pre>";

if($sandbox){
echo '<a href="https://www.sandbox.paypal.com'.$returnArray['AuthorizationUrl'].'" >Click Here to Obtain User Consent</a>';
}
else{
    echo '<a href="https://www.paypal.com'.$returnArray['AuthorizationUrl'].'" >Click Here to Obtain User Consent</a>';
}