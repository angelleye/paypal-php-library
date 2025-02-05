<?php
require_once('../../../vendor/autoload.php');
require_once('../../../includes/config.php');
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Create and Execute Payment using PayPal w/ Line items | REST | Order Review | PHP Class Library | Angell EYE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
    <!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
    <!--script src="/assets/js/less-1.3.3.min.js"></script-->
    <!--append ‘#!watch’ to the browser URL, then refresh the page. -->

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/images/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../../assets/images/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../../assets/images/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../../assets/images/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="../../assets/images/favicon.png">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../assets/js/scripts.js"></script>
</head>

<body>
<div class="container">
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div id="header" class="row clearfix">
                <div class="col-md-6 column">
                    <div id="angelleye_logo"> <a href="/"><img alt="Angell EYE PayPal PHP Class Library Demo" src="../../assets/images/logo.png"></a> </div>
                </div>
                <div class="col-md-6 column">
                    <div id="paypal_partner_logo"> <img alt="PayPal Partner and Certified Developer" src="../../assets/images/paypal-partner-logo.png"/> </div>
                </div>
            </div>
            <h2 align="center">Order Review</h2>
            <p class="bg-info">
                We have now reached the final thank you / receipt page and the payment has been captured!
                <br /><br />We have added the PayPal Order ID and Transaction ID
                to the Billing Information, which was provided in the <strong>CaptureOrder</strong> response.
            </p>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Sku</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th class="center">Price</th>
                    <th class="center">QTY</th>
                    <th class="center">Total</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($_SESSION['orderItems'] as $cart_item) {
                    ?>
                    <tr>
                        <td><?php echo isset($cart_item['sku']) ? $cart_item['sku'] : ''; ?></td>
                        <td><?php echo isset($cart_item['name']) ? $cart_item['name'] : ''; ?></td>
                        <td><?php echo isset($cart_item['description']) ? $cart_item['description'] : '' ; ?></td>
                        <td class="center"> $<?php echo isset($cart_item['unit_amount']['value']) ? number_format($cart_item['unit_amount']['value'],2) : ''; ?></td>
                        <td class="center"><?php echo isset($cart_item['quantity']) ? $cart_item['quantity'] : ''; ?></td>
                        <td class="center"> $<?php echo number_format($cart_item['quantity'] * $cart_item['unit_amount']['value'],2); ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            <div class="row clearfix">
                <div class="col-md-4 column">
                    <p><strong>Billing Information</strong></p>
                    <p>
                        <?php
                        echo (!empty($_SESSION['payer_info']['first_name'])) ? $_SESSION['payer_info']['first_name'].' ' : '';
                        echo (!empty($_SESSION['payer_info']['last_name'])) ? $_SESSION['payer_info']['last_name'].' ' : '';
                        echo (!empty($_SESSION['payer_info']['payer_id'])) ? '<br/><strong>Payer ID : </strong>'.$_SESSION['payer_info']['payer_id'] : '';
                        echo (!empty($_SESSION['payer_info']['email_address'])) ? '<br/><strong>Email ID : </strong>'.$_SESSION['payer_info']['email_address'] : '';
                        echo (!empty($_SESSION['checkout_order_id'])) ? '<br/><strong>PayPal Order ID : </strong>'. $_SESSION['checkout_order_id'] : '';
                        echo (!empty($_SESSION['order_transaction_id'])) ? '<br/><strong>Transaction ID : </strong>'. $_SESSION['order_transaction_id'] : '';
                        ?>
                    </p>
                </div>
                <div class="col-md-4 column">
                    <p><strong>Shipping Information</strong></p>
                    <p>
                        <?php
                        echo (!empty($_SESSION['shipping_address']['address_line_1'])) ? $_SESSION['shipping_address']['address_line_1'] : '';
                        echo (!empty($_SESSION['shipping_address']['admin_area_2'])) ? '<br/>'.$_SESSION['shipping_address']['admin_area_2'] : '';
                        echo (!empty($_SESSION['shipping_address']['admin_area_1'])) ? '<br/>'.$_SESSION['shipping_address']['admin_area_1'].', ' : '';
                        echo (!empty($_SESSION['shipping_address']['postal_code'])) ? '<br/>'.$_SESSION['shipping_address']['postal_code'].', ' : '';
                        echo (!empty($_SESSION['shipping_address']['country_code'])) ? $_SESSION['shipping_address']['country_code'] : '';
                        ?>
                    </p>
                </div>
                <div class="col-md-4 column">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td><strong> Subtotal</strong></td>
                            <td> $<?php echo isset($_SESSION['paymentDetails']['item_total']) ? number_format($_SESSION['paymentDetails']['item_total'],2) : ''; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Shipping</strong></td>
                            <td>$<?php echo isset($_SESSION['paymentDetails']['shipping']) ? number_format($_SESSION['paymentDetails']['shipping'],2) : ''; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tax</strong></td>
                            <td>$<?php echo isset($_SESSION['paymentDetails']['tax_total']) ? number_format($_SESSION['paymentDetails']['tax_total'],2) : ''; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Handling fee</strong></td>
                            <td>$<?php echo isset($_SESSION['paymentDetails']['handling']) ? number_format($_SESSION['paymentDetails']['handling'],2) : ''; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Insurance</strong></td>
                            <td>$<?php echo isset($_SESSION['paymentDetails']['insurance']) ? number_format($_SESSION['paymentDetails']['insurance'],2) : ''; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Shipping Discount</strong></td>
                            <td>$<?php echo isset($_SESSION['paymentDetails']['shipping_discount']) ? number_format($_SESSION['paymentDetails']['shipping_discount'],2) : ''; ?></td>
                        </tr>

                        <tr>
                            <td><strong>Grand Total</strong></td>
                            <td>$<?php echo isset($_SESSION['amount']['Total']) ? number_format($_SESSION['amount']['Total'],2) : ''; ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php
session_destroy();
?>