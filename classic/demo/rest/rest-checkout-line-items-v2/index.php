<?php
require_once '../../../includes/config.php';

/**
 * Here we are building a very simple, static shopping cart to use
 * throughout this demo.  In most cases, you will working with a dynamic
 * shopping cart system of some sort.
 */
$_SESSION['currency'] = 'USD';

$items[0] = array(
    'sku'         => '123',                                    // Stock keeping unit corresponding (SKU) to item.Maximum length: 127.
    'name'        => 'Hat',                                    // Required if you are adding item array. The item name or title. 127 characters max.
    'description' => 'Kansas City Chiefs Large Multi-Fit Hat', // The detailed item description. Maximum length: 127.
    'quantity'    => 1,                                        // The item quantity. Must be a whole number. Maximum length: 10.
    'unit_amount' => array(
        'value' => 7.50,
        'currency_code' => $_SESSION['currency']
    ),                                     // Required if you are adding item array. The item price or rate per unit. 32 characters max.
    'tax'         => array(
        'value' => 0.00,
        'currency_code' => $_SESSION['currency']
    ),                                     // The item tax for each unit.
    'category'    => 'PHYSICAL_GOODS'                          // The item category type. DIGITAL_GOODS | PHYSICAL_GOODS
);

$items[1] = array(
    'sku'         => '678',                                 // Stock keeping unit corresponding (SKU) to item.Maximum length: 127.
    'name'        => 'Handbag',                             // Required if you are adding item array. The item name or title. 127 characters max.
    'description' => 'Small, leather handbag.',             // The detailed item description. Maximum length: 127.
    'quantity'    => 2,                                     // The item quantity. Must be a whole number. Maximum length: 10.
    'unit_amount' => array(
        'value' => 5.00,
        'currency_code' => $_SESSION['currency']
    ),                                  // Required if you are adding item array. The item price or rate per unit. 32 characters max.
    'tax'         => array(
        'value' => 0.00,
        'currency_code' => $_SESSION['currency']
    ),                                  // The item tax for each unit.
    'category'    => 'PHYSICAL_GOODS'                       // The item category type. DIGITAL_GOODS | PHYSICAL_GOODS
);


$orderItems = $items;

$_SESSION['orderItems'] = $orderItems;

$paymentDetails = array(
    'item_total' => 17.50,
    'shipping' => 0.00,
    'tax_total' => 0.00,
    'handling' => 0.00,
    'insurance' => 0.00,
    'shipping_discount' => 0.00
);
$_SESSION['paymentDetails'] = $paymentDetails;

/**
 * below code is for the grand total
 */
$amount['Total'] = number_format($paymentDetails['item_total'] + $paymentDetails['shipping'] + $paymentDetails['tax_total'] + $paymentDetails['handling'] + $paymentDetails['insurance'] + $paymentDetails['shipping_discount'] ,2);
$_SESSION['amount']['Total'] = $amount['Total'];
?>
<!DOCTYPE html lang="en">
<head>
    <meta charset="utf-8">
    <title>PayPal Checkout JS SDK | Smart Button | PHP Class Library | Angell EYE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="">
    <meta name="author" content="">

    <!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
    <!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
    <!--script src="../assets/js/less-1.3.3.min.js"></script-->
    <!--append ‘#!watch’ to the browser URL, then refresh the page. -->

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/css/style.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="../../../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../../assets/images/apple-touch-icon-144-precomposed.png">
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
            <h2 align="center">Shopping Cart</h2>
            <p class="bg-info">
                Here we are using a basic shopping cart for display purposes,
                and we are implementing the <a target="_blank" href="https://developer.paypal.com/docs/api/orders/v2/">Orders API v2.</a>
                In this example we have Order intent set to <b>Capture</b>, which will process the payment immediately.
            </p>
            <p class="bg-info"  id="buyer_login_info">To complete the demo, click the PayPal button and use the following credentials to login to PayPal.<br /><br />
                Email Address:  paypal-buyer@angelleye.com<br />
                Password:  paypalphp
            </p>
            <div style="padding-top: 50px"></div>
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
                <tbody>
                <?php
                foreach($orderItems as $cart_item) {
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
                    <div style="display:none" class="billing">
                        <p><strong>Billing Information</strong></p>
                        <p><strong>Name </strong><span id="recipient_name"></span></p>
                        <p><strong>Email </strong><span id="semail"></span></p>
                        <p><strong>Payer ID </strong><span id="payer_id"></span></p>
                        <p><strong>PayPal Order ID </strong><span id="order_id"></span></p>
                        <p id="order_status_p" style="display: none"><strong>Order Payment Status </strong><span id="order_payment_status"></span></p>
                        <p id="transaction_id" style="display: none"><strong>Transaction ID </strong><span id="txn_id"></span></p>
                    </div>
                </div>
                <div class="col-md-4 column">
                    <div style="display: none" class="shipping">
                        <p><strong>Shipping Information</strong></p>
                        <p><strong>Name </strong><span id="recipient"></span></p>
                        <p><strong>Address </strong><span id="line1"></span></p>
                        <p><strong>City </strong><span id="city"></span></p>
                        <p><strong>State </strong><span id="state"></span></p>
                        <p><strong>Postal Code </strong><span id="postal_code"></span></p>
                        <p><strong>Country Code </strong><span id="country_code"></span></p>
                    </div>
                </div>
                <div class="col-md-4 column">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td><strong> Subtotal</strong></td>
                            <td id="subtotal_update"> $<?php echo number_format($paymentDetails['item_total'], 2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Shipping</strong></td>
                            <td id="shipping_update">$<?php echo number_format($paymentDetails['shipping'], 2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Tax</strong></td>
                            <td>$<?php echo number_format($paymentDetails['tax_total'], 2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Handling fee</strong></td>
                            <td>$<?php echo number_format($paymentDetails['handling'], 2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Insurance</strong></td>
                            <td>$<?php echo number_format($paymentDetails['insurance'], 2); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Shipping Discount</strong></td>
                            <td>$<?php echo number_format($paymentDetails['shipping_discount'], 2); ?></td>
                        </tr>

                        <tr>
                            <td><strong>Grand Total</strong></td>
                            <td id="total_update">$<?php echo number_format($amount['Total'], 2); ?></td>
                        </tr>
                        <tr>
                            <td class="center" colspan="2">
                                <a role="button" href="CreateCaptureOrder.php"><img src="https://www.paypalobjects.com/webstatic/en_AU/i/buttons/btn_paywith_primary_l.png"/></a>
                            </td>
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