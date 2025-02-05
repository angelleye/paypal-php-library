<?php
require_once('../../../includes/config.php');

/**
 * Here we are building a very simple, static shopping cart to use
 * throughout this demo.  In most cases, you will working with a dynamic
 * shopping cart system of some sort.
 */

$currency = 'USD';
$_SESSION['intent'] = 'sale';
$_SESSION['invoiceNumber'] = 'AE-INVC-'.rand(0,100);
$_SESSION['NoteToPayer'] = 'Contact us for any questions on your order.';     //free-form field for the use of clients to pass in a message to the payer.

$_SESSION['items'][0] = array(
    'Sku'         => '123',                                     // Stock keeping unit corresponding (SKU) to item.
    'Name'        => 'Hat',                                     // Item name. 127 characters max.
    'Description' => 'Kansas City Chiefs Large Multi-Fit Hat',  // Description of the item. Only supported when the `payment_method` is set to `paypal`.
    'Quantity'    => '1',                                       // Number of a particular item. 10 characters max
    'Price'       => '7.50',                                    // Item cost. 10 characters max.
    'Currency'    => $currency,                                 // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
    'Tax'         => ''                                         // Tax of the item. Only supported when the `payment_method` is set to `paypal`.
);

$_SESSION['items'][1] = array(
    'Sku'         => '678',                                 // Stock keeping unit corresponding (SKU) to item.
    'Name'        => 'Handbag',                             // Item name. 127 characters max.
    'Description' => 'Small, leather handbag.',             // Description of the item. Only supported when the `payment_method` is set to `paypal`.
    'Quantity'    => '2',                                   // Number of a particular item. 10 characters max
    'Price'       => '10.00',                               // Item cost. 10 characters max.
    'Currency'    => $currency,                             // 3-letter [currency code](https://developer.paypal.com/docs/integration/direct/rest_api_payment_country_currency_support/).
    'Tax'         => ''                                     // Tax of the item. Only supported when the `payment_method` is set to `paypal`.
);

$_SESSION['orderItems'] = $_SESSION['items'];

$_SESSION['paymentDetails'] = array(
    'Subtotal' => '27.50',                                   // Amount of the subtotal of the items. **Required** if line items are specified. 10 characters max, with support for 2 decimal places.
    'Shipping' => '0.00',                                    // Amount charged for shipping. 10 characters max with support for 2 decimal places.
    'Tax'      => '0.00',                                    // Amount charged for tax. 10 characters max with support for 2 decimal places.
    'GiftWrap' => '0.00'                                     // Amount being charged as gift wrap fee.
);

/**
 * below code is for the grand total
 */
$_SESSION['amount']['Total'] = number_format($_SESSION['paymentDetails']['Subtotal'] + $_SESSION['paymentDetails']['Shipping'] + $_SESSION['paymentDetails']['Tax'] + $_SESSION['paymentDetails']['GiftWrap'],2);
$_SESSION['amount']['Currency'] = $currency;
?>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Create and Execute Payment using PayPal w/ Line items | REST | PHP Class Library | Angell EYE</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <script src="../../assets/js/html5shiv.js"></script>
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
      <h2 align="center">Shopping Cart</h2>
      <p class="bg-info">Here we are using a basic shopping cart for display purposes.
          <br /><br />
          For this basic demo, all we are sending to PayPal is the payment details, order items, and order total. We are assuming that we have not collected any
      billing or shipping information from the buyer yet because we'll be obtaining those details from PayPal 
      after the user logs in and is returned back to the site.</p>
      <p class="bg-info">To complete the demo, click the PayPal button and use the following credentials to login for payment.<br /><br />
      Email Address: paypal-buyer@angelleye.com<br />
      Password:  paypalphp
      </p>
        <div style="padding-top: 50px"></div>
        <div class="row clearfix">
            <div class="col-md-4 column">
                <table class="table">
                    <tbody>
                    <tr>
                        <td><strong>Intent</strong></td>
                        <td><?php echo isset($_SESSION['intent']) ? $_SESSION['intent'] : ''; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Invoice Number</strong></td>
                        <td><?php echo isset($_SESSION['invoiceNumber']) ? $_SESSION['invoiceNumber'] : ''; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Note To Payer</strong></td>
                        <td><?php echo isset($_SESSION['NoteToPayer']) ? $_SESSION['NoteToPayer'] : ''; ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
            <td><?php echo isset($cart_item['Sku']) ? $cart_item['Sku'] : ''; ?></td>
            <td><?php echo isset($cart_item['Name']) ? $cart_item['Name'] : ''; ?></td>
            <td><?php echo isset($cart_item['Description']) ? $cart_item['Description'] : '' ; ?></td>
            <td class="center"> $<?php echo isset($cart_item['Price']) ? number_format($cart_item['Price'],2) : ''; ?></td>
            <td class="center"><?php echo isset($cart_item['Quantity']) ? $cart_item['Quantity'] : ''; ?></td>
            <td class="center"> $<?php echo number_format($cart_item['Quantity'] * $cart_item['Price'],2); ?></td>
          </tr>
          <?php
    }
    ?>
        </tbody>
      </table>
      <div class="row clearfix">
        <div class="col-md-4 column"> </div>
        <div class="col-md-4 column"> </div>
        <div class="col-md-4 column">
          <table class="table">
            <tbody>
              <tr>
                <td><strong> Subtotal</strong></td>
                <td> $<?php echo isset($_SESSION['paymentDetails']['Subtotal']) ? number_format($_SESSION['paymentDetails']['Subtotal'],2) : ''; ?></td>
              </tr>
              <tr>
                <td><strong>Shipping</strong></td>
                <td>$<?php echo isset($_SESSION['paymentDetails']['Shipping']) ? number_format($_SESSION['paymentDetails']['Shipping'],2) : ''; ?></td>
              </tr>
              <tr>
                <td><strong>Tax</strong></td>
                <td>$<?php echo isset($_SESSION['paymentDetails']['Tax']) ? number_format($_SESSION['paymentDetails']['Tax'],2) : ''; ?></td>
              </tr>
              <tr>
                  <td><strong>Gift Wrap</strong></td>
                  <td>$<?php echo isset($_SESSION['paymentDetails']['GiftWrap']) ? number_format($_SESSION['paymentDetails']['GiftWrap'],2) : ''; ?></td>
              </tr>
              <tr>
                <td><strong>Grand Total</strong></td>
                <td>$<?php echo isset($_SESSION['amount']['Total']) ? number_format($_SESSION['amount']['Total'],2) : ''; ?></td>
              </tr>
              <tr>
                  <td class="center" colspan="2"><a href="CreatePaymentUsingPayPal.php"><img src="https://www.paypalobjects.com/webstatic/en_AU/i/buttons/btn_paywith_primary_l.png"></a></td>
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