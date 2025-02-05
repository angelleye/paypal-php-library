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

$redirectUri = '';  // Uri on merchant website to where the user must be redirected to post paypal login

// @param array $scope The access privilges that you are requesting for from the user. Pass empty array for all scopes.
$Scope   = array(); 

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
?>