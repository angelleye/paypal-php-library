<?php
if (!session_id())
    session_start();

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
                    <h2 align="center">Integrated Signup Pre-Fill Onboarding API Callback</h2>
                    <?php
                    if (isset($_REQUEST)) {
                        if (isset($_REQUEST['merchantIdInPayPal'])) {
                            $AccountDetail = $PayPal->getAccountDetails('VVUB43QZJ6TEU', $_REQUEST['merchantIdInPayPal']);                                                                                    
                            if (!empty($AccountDetail['RAWRESPONSE']) && isset($AccountDetail['RAWRESPONSE']['merchant_id'])) {
                                ?>
                                <div class="clearfix"></div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>        
                                                <td><strong>Primary Email</strong></td>
                                                <td class="text-primary"><?php echo isset($AccountDetail['RAWRESPONSE']['primary_email']) ? $AccountDetail['RAWRESPONSE']['primary_email'] : ''; ?></td>
                                            </tr>
                                            <tr>        
                                                <td><strong>Merchant Id</strong></td>
                                                <td class="text-primary"><?php echo isset($_REQUEST['merchantIdInPayPal']) ? $_REQUEST['merchantIdInPayPal'] : ''; ?></td>
                                            </tr>
                                            <tr>        
                                                <td><strong>Permissions Granted</strong></td>
                                                <td class="text-primary"><?php echo isset($_REQUEST['permissionsGranted']) ? $_REQUEST['permissionsGranted'] : ''; ?></td>
                                            </tr>
                                            <tr>        
                                                <td><strong>Account Status</strong></td>
                                                <td class="text-primary"><?php echo isset($_REQUEST['accountStatus']) ? $_REQUEST['accountStatus'] : ''; ?></td>
                                            </tr>
                                            <tr>        
                                                <td><strong>Consent Status</strong></td>
                                                <td class="text-primary"><?php echo isset($_REQUEST['consentStatus']) ? $_REQUEST['consentStatus'] : ''; ?></td>
                                            </tr>
                                            <tr>        
                                                <td><strong>Product Intent ID</strong></td>
                                                <td class="text-primary"><?php echo isset($_REQUEST['productIntentID']) ? $_REQUEST['productIntentID'] : ''; ?></td>
                                            </tr>
                                            <tr>        
                                                <td><strong>Email Confirmed</strong></td>
                                                <td class="text-primary"><?php echo isset($_REQUEST['isEmailConfirmed']) ? $_REQUEST['isEmailConfirmed'] : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Accepted Permissions</strong></td>
                                                <td class="text-primary">
                                                    <ul>
                                                        <?php
                                                        if(isset($AccountDetail['RAWRESPONSE']['permissions'])){
                                                            foreach ($AccountDetail['RAWRESPONSE']['permissions'] as $permission) {
                                                                echo "<li class='text-primary'>{$permission}</li>";
                                                            }                                                        
                                                        }
                                                        ?>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Products</td>
                                                <td class="text-primary"><?php echo isset($AccountDetail['RAWRESPONSE']['products'][0]['name']) ? $AccountDetail['RAWRESPONSE']['products'][0]['name'] : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Return Message</td>
                                                <td class="text-primary"><?php echo isset($_REQUEST['returnMessage']) ? $_REQUEST['returnMessage'] : '';  ?></td>
                                            </tr>
                                            <tr>
                                                <td> Payments Receivable</td>
                                                <td class="text-primary"><?php echo isset($AccountDetail['RAWRESPONSE']['payments_receivable']) ? $AccountDetail['RAWRESPONSE']['payments_receivable'] : ''; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <?php
                            } else {
                                echo "<pre>";
                                print_r($AccountDetail);
                                echo "</pre>";
                            }
                        }
                    }
                    ?>                                        
                </div>
            </div>
        </div>
    </body>
</html>