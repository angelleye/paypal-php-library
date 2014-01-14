<?php
//require the auth class
require_once('../includes/config.php');
require_once('../includes/paypal.class.php');
require_once('../includes/paypal.access.class.php');

//initialize a new PayPal Access instance
$ppaccess_config = array(
		'Sandbox'=> $sandbox, 
		'Key'=>'c3ea2462dedb77e0d0708d00c7d86411', 
		'Secret'=>'dc8e58df145d1fed', 
		'Scopes'=>'openid', 
		'ReturnURL'=>'http://paypal.angelleye.com/standard/samples/Access.php'
		);
$ppaccess = new PayPalAccess($ppaccess_config);

//if the code parameter is available, the user has gone through the auth process
$code = isset($_GET['code']) ? $_GET['code'] : '';
if($code != '')
{
    //make request to exchange code for an access token
    $token = $ppaccess->get_access_token($code);
    
    //use access token to get user profile
    $profile = $ppaccess->get_profile();
    
    //make request to refresh an expired access token
    $refreshed = $ppaccess->refresh_access_token();
    
    //validate the id token and provide back validation object
    $verify = $ppaccess->validate_token();
    
    //log the user out
    $ppaccess->end_session();
//if the code parameter is not available, the user should be pushed to auth
}
else
{
    //handle case where there was an error during auth (e.g. the user didn't log in / refused permissions / invalid_scope)
    if (isset($_GET['error_uri']))
    {
        echo "error";
    //this is the first time the user has come to log in
    }
    else
    {
        //get auth url and redirect user browser to PayPal to log in
        $url = $ppaccess->get_auth_url();
        header("Location: $url");
    }
}