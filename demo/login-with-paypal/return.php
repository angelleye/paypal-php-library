<?php
require_once('../../includes/config.php');
require_once('../../autoload.php');

use PayPal\Api\OpenIdSession;
use PayPal\Api\OpenIdTokeninfo;;
use PayPal\Api\OpenIdUserinfo;
use PayPal\Exception\PayPalConnectionException;

$_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($rest_client_id,$rest_client_secret)
            );

if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $code = $_GET['code'];
    try {        
        $accessToken = OpenIdTokeninfo::createFromAuthorizationCode(array('code' => $code), null, null, $_api_context);
        $params = array('access_token' => $accessToken->getAccessToken());
        $userInfo = OpenIdUserinfo::getUserinfo($params, $_api_context);
        echo "<pre>";
        print_r($userInfo->toArray());
    } catch (PayPalConnectionException $ex) {
        echo "<pre>";
        print_r($ex);
        exit;
    }
}