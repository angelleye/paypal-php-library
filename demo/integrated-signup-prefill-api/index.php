<?php
require_once('../../includes/config.php');
require_once('../../autoload.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'Sandbox' => $sandbox,    
    'LogResults' => $log_results, 
    'LogPath' => $log_path
);

$PayPal = new angelleye\PayPal\PayPal_IntegratedSignup($configArray);

$error = false;
$partner_specific_identifiers = array(
    "type" => 'TRACKING_ID',                          // (Required) Type of partner identifier.Valid values: TRACKING_ID.
    "value" => '12d29842a912a'                       // Partner identifier. The partner identifier is a value for your own use in tracking the merchant and will be returned to you when the merchant is redirected back to your site from PayPal. Only alphanumeric characters are allowed in this field.
);

$requested_capabilities = array();         // Contains information on the capabilities, including specifics of API provisioning, that is being requested for the new account.

$api_integration_preference = array();     // Contains information on the API integration to be performed with the partner.
$api_integration_preference['classic_api_integration_type'] = 'THIRD_PARTY';             // The type of integration that will be performed with the partner. Valid values: THIRD_PARTY (indicates that third party permissions are to be granted from the merchant to the partner), FIRST_PARTY_INTEGRATED (indicates that first party API credentials are to be obtained from the merchant), FIRST_PARTY_NON_INTEGRATED (indicates that first party API credentials are to be generated and displayed to the merchant at the end of the signup flow).

$classic_third_party_details = array();     // Contains information on the third party permissions that are to be granted to the partner. Only valid when classic_api_integration_type is set to THIRD_PARTY.
$permission_list = array(
    "0" => 'EXPRESS_CHECKOUT',              // provides access to the SetExpressCheckout, GetExpressCheckoutDetails, DoExpressCheckoutPayment, and GetPalDetails APIs.
    "1" => 'REFUND',                        // provides access to the Refund and RefundTransaction APIs.
    "2" => 'AUTH_CAPTURE',                  // provides access to the DoAuthorization,DoCapture, DoReauthorization, and DoVoid APIs.
    "3" => 'TRANSACTION_DETAILS',           // provides access to the GetTransactionDetails API.
    "4" => 'TRANSACTION_SEARCH',            // provides access to the TransactionSearch API.
    "5" => 'REFERENCE_TRANSACTION',         // provides access to the BillAgreementUpdate and DoReferenceTransaction APIs.
    "6" => 'DIRECT_PAYMENT',                // provides access to the DoDirectPayment API
    "7" => 'BUTTON_MANAGER',                // provides access to the BMButtonSearch, BMCreateButton, BMGetButtonDetails, BMGetInventory, BMManageButtonStatus, BMSetInventory, and BMUpdateButton APIs
    "8" => 'ACCOUNT_BALANCE',               // provides access to the GetBalance API
    "9" => 'RECURRING_PAYMENTS',            // provides access to the CreateRecurringPaymentProfile, GetRecurringPaymentsProfileDetails, ManageRecurringPaymentsProfileStatus, UpdateRecurringPaymentsProfile, and BillOutstandingAmount APIs
    "10" => 'INVOICING',                    // provides access to the CancelInvoice, CreateAndSendInvoice, CreateInvoice, DeleteInvoice, GenerateInvoiceNumber, GetInvoiceDetails, MarkInvoiceAsPaid, MarkInvoiceAsRefunded, MarkInvoiceAsUnpaid, RemindInvoice, SearchInvoice, SendInvoice, and UpdateInvoice APIs
    "11" => 'ACCESS_BASIC_PERSONAL_DATA'    // provides you access to the merchant’s personal information
);
/* $classic_first_party_details = array(); 
 * classic_first_party_details
 * Indicates whether API credentials generated for the merchant
   should contain an API signature or an API certificate. Only valid
   when classic_api_integration_type is set to FIRST_PARTY_INTEGRATED
   or FIRST_PARTY_NON_INTEGRATED.
 * 
$api_integration_preference['classic_api_integration_type'] = 'FIRST_PARTY_INTEGRATED';
$api_integration_preference['classic_first_party_details'] = 'SIGNATURE';  // Valid values: SIGNATURE (indicates that API credentials generated for the merchant should contain an API signature) CERTIFICATE (indicates that API credentials generated for the merchant should contain an API certificate)
*/
$classic_third_party_details['permission_list'] = $permission_list;  
$api_integration_preference['classic_third_party_details'] = $classic_third_party_details;  // Contains information on the third party permissions that are to be granted to the partner. Only valid when classic_api_integration_type is set to THIRD_PARTY
$api_integration_preference['partner_id'] = 'VVUB43QZJ6TEU';                // Payer ID of the partner with whom the merchant will integrate. This will generally be your payer ID.
$requested_capabilities['api_integration_preference'] = $api_integration_preference;  
$requested_capabilities['capability'] = 'API_INTEGRATION';                // Describes the capability being requested. Valid values: API_INTEGRATION.

$web_experience_preference = array(
    "action_renewal_url" => '',                           // URL where the merchant will be returned to in the event that the token (issued by this call) has expired.
    "partner_logo_url" => '',                            // URL of an image which will be displayed to the merchant during the signup flow. This allows you to co-brand the signup pages with your logo.
    "return_url" => $domain .'demo/integrated-signup-prefill-api/IntegratedSignup_Callback.php'    // URL where the merchant will be returned to after they complete the signup flow on PayPal.
);
$collected_consents = array(
    "granted" => true,                                //Indicates whether consent was obtained. If set to false, PayPal will ignore all of the data passed in the customer_data top-level object.
    "type" => "SHARE_DATA_CONSENT"                   //Indicates the type of consent obtained from the merchant. Valid values: SHARE_DATA_CONSENT (indicates that you have obtained consent from the merchant to share their data with PayPal).
);
$products = array(
    "0" => 'EXPRESS_CHECKOUT'              // Indicates which PayPal products the merchant should be enrolled in. Valid values: EXPRESS_CHECKOUT.   
);
$requestData = array();
$requestData['collected_consents'][0] = $collected_consents;
$requestData['products'] = $products;
$requestData['requested_capabilities'][0] = $requested_capabilities;
$requestData['web_experience_preference'] = $web_experience_preference;

$responseData = $PayPal->IntegratedSignup($requestData);
if(isset($responseData['RAWRESPONSE']['links'])){
    $action_url = $responseData['RAWRESPONSE']['links'][1]['href'];
}
else{
    $error = true;
    $errorArray = $responseData;
}
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Integrated Signup Pre-Fill Onboarding API Demo | PHP Class Library | Angell EYE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
        <!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
        <!--script src="/assets/js/less-1.3.3.min.js"></script-->
        <!--append ‘#!watch’ to the browser URL, then refresh the page. -->

        <link href="../../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/style.css" rel="stylesheet">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="/assets/js/html5shiv.js"></script>
            <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/images/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/images/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/images/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="../assets/images/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="../assets/images/favicon.png">
        <script type="text/javascript" src="../assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../assets/js/scripts.js"></script>
    </head>

    <body>
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-12 column">
                    <div id="header" class="row clearfix">
                        <div class="col-md-6 column">
                            <div id="angelleye_logo"> <a href="/"><img alt="Angell EYE PayPal PHP Class Library Demo" src="../assets/images/logo.png"></a> </div>
                        </div>
                        <div class="col-md-6 column">
                            <div id="paypal_partner_logo"> <img alt="PayPal Partner and Certified Developer" src="../assets/images/paypal-partner-logo.png"/> </div>
                        </div>
                    </div>
                    <h2 align="center">Integrated Signup Pre-Fill Onboarding API (Third Party Demo)</h2>      
                    <?php
                            if(isset($error) && $error==true){
                                echo '<br/><div><p class="text-danger"><span>Error Name :</span>'.$errorArray['RAWRESPONSE']['name'].'</p>';
                                echo '<p class="text-danger"><span>Error Message :</span>'.$errorArray['RAWRESPONSE']['message'].'</p>';
                                echo '<p class="text-danger"><span>Debug ID :</span>'.$errorArray['RAWRESPONSE']['debug_id'].'</p>';
                                echo '<p class="text-danger"><span>Details</span><ul>';
                                foreach ($errorArray['RAWRESPONSE']['details'] as $detail) {
                                    echo '<li class="text-danger">Field : '.$detail['field'].'</li>';
                                    echo '<li class="text-danger">Issue : '.$detail['issue'].'</li>';
                                }
                                echo "</ul></p>";
                                echo "</div>";
                            }
                            else {
                        ?>
                    <div class="bg-info">
                        
                        <strong>With integrated signup, you POST a PayPal signup link on your website for your merchants and PayPal handles the rest.</strong>
                        <br>Here’s how it works:
                        <ul> 
                            <li>If your merchants already have a PayPal Business account, the merchants must agree to grant permission to you, the partner, to make payments on their behalf. After they agree, they are ready to accept PayPal payments.</li>
                            <li>If your merchants do not have a PayPal Business account, they are guided to create one before granting you consent.</li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                    <br>      
                    <div class="text-center">              
                        <a href="<?php echo $action_url; ?>" class="btn btn-lg btn-primary">Connect To PayPal</a>              
                    </div>
                   <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>