<?php
// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

use PayPal\Api\OpenIdTokeninfo;
use PayPal\Exception\PayPalConnectionException;

$_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($rest_client_id,$rest_client_secret)
            );
// ### User Consent Response
// PayPal would redirect the user to the redirect_uri mentioned when creating the consent URL.
// The user would then able to retrieve the access token by getting the code, which is returned as a GET parameter.
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $code = $_GET['code'];
    try {
        // Obtain Authorization Code from Code, Client ID and Client Secret
        $accessToken = OpenIdTokeninfo::createFromAuthorizationCode(array('code' => $code), null, null, $_api_context);
        echo "<pre>";
        print_r($accessToken->toArray());
    } catch (PayPalConnectionException $ex) {
        echo "<pre>";
        print_r($ex);
        exit;
    }
}