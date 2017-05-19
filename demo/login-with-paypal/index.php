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

$redirectUri = 'http://localhost/paypal-php-library/demo/login-with-paypal/return.php?success=true';

$Scope   = array('openid', 'profile', 'address', 'email', 'phone',
                'https://uri.paypal.com/services/paypalattributes',
                'https://uri.paypal.com/services/expresscheckout',
                'https://uri.paypal.com/services/invoicing'); 

$redirectUrl = OpenIdSession::getAuthorizationUrl(
            $redirectUri,$Scope,
            null,
            null,
            null,
            $_api_context
        );
?>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PayPal Express Checkout Basic Demo | PHP Class Library | Angell EYE</title>
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
      <h2 align="center">Login With PayPal</h2>      
      <p class="bg-info">To complete the demo, click the login with PayPal button and use the following credentials to login to PayPal.<br /><br />
      Email Address:  paypalphp@angelleye.com<br />
      Password:  paypalphp
      </p>         
    </div>
    <div class="clearfix"></div>
    <div class="col-md-12">
        <div class="form-group">
            <label>Redirect Uri : </label>  http://localhost/paypal-php-library/demo/login-with-paypal/return.php?success=true
        </div>
        <div class="form-group">
            <label>Scope : </label> openid, profile, address, email, phone ,<br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; https://uri.paypal.com/services/invoicing, https://uri.paypal.com/services/paypalattributes ,https://uri.paypal.com/services/expresscheckout
        </div>
    </div>
  </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <?php 
                $paypal_url='';
                if($sandbox){
                    $paypal_url = 'https://www.sandbox.paypal.com';
                }
                else{
                    $paypal_url = 'https://www.paypal.com';
                }                
            ?>
            <a href="<?php echo $paypal_url.$redirectUrl; ?>">
                <img src="https://www.paypalobjects.com/webstatic/en_US/developer/docs/lipp/loginwithpaypalbutton.png" alt="Login with PayPal" style="cursor: pointer"/>
            </a>    
        </div>
    </div>  
</div>
</body>
</html>