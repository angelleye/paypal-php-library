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

// ### User Consent Response
// PayPal would redirect the user to the redirect_uri mentioned when creating the consent URL.
// The user would then able to retrieve the access token by getting the code, which is returned as a GET parameter.

if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $code = isset($_GET['code']) ? $_GET['code'] : '';
    $returnArray = $PayPal->GetUserConsentRedirect($code);
    echo "<pre>";
    print_r($returnArray);
}
else{
    echo "Something went wrong.";
}