<?php

// #Execute Agreement
// Use this call to execute an agreement after the buyer approves it
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($rest_client_id,$rest_client_secret)
            );
echo "<pre>";

// ## Approval Status
// Determine if the user accepted or denied the request
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $token = $_GET['token'];
    $agreement = new \PayPal\Api\Agreement();
    try {
        // ## Execute Agreement
        // Execute the agreement by passing in the token
        $agreement->execute($token, $_api_context);
    } catch (Exception $ex) {
       var_dump("Executed an Agreement", "Agreement", $agreement->getId(), $_GET['token'], $ex);
        exit();
    }

    var_dump("Executed an Agreement", "Agreement", $agreement->getId(), $_GET['token'], $agreement);

    // ## Get Agreement
    // Make a get call to retrieve the executed agreement details
    try {
        $agreement = \PayPal\Api\Agreement::get($agreement->getId(), $_api_context);
    } catch (Exception $ex) {
        
        var_dump("Get Agreement", "Agreement", $ex);
        exit();
    }    
    var_dump("Get Agreement", "Agreement", $agreement->getId(), $agreement);
} else {    
    var_dump("User Cancelled the Approval");
}