<?php
require_once('../../includes/config.php');

/**
 * Here we are building a very simple, static shopping cart to use
 * throughout this demo.  In most cases, you will working with a dynamic
 * shopping cart system of some sort.
 */
$_SESSION['items'][0] = array(
    'id' => '123-ABC',
    'name' => 'Widget',
    'qty' => '2',
    'price' => '9.99',
);

$_SESSION['items'][1] = array(
    'id' => 'XYZ-456',
    'name' => 'Gadget',
    'qty' => '1',
    'price' => '4.99',
);
$_SESSION['shopping_cart'] = array(
	'items' => $_SESSION['items'],
	'subtotal' => 24.97,
	'shipping' => 0,
	'handling' => 0,
	'tax' => 0,
);
$_SESSION['shopping_cart']['grand_total'] = number_format($_SESSION['shopping_cart']['subtotal'] + $_SESSION['shopping_cart']['shipping'] + $_SESSION['shopping_cart']['handling'] + $_SESSION['shopping_cart']['tax'],2);
?>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PayPal Express Checkout In-Context | PHP Class Library | Angell EYE</title>
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
      <h2 align="center">Shopping Cart</h2>
      <p class="bg-info">The In-Context PayPal Express Checkout helps improve conversion rates with an easier way to pay online. 
          The simplified design speeds buyers through payment in as few as one or two clicks — without leaving your website — 
          for a secure and seamless check out. A consistent experience for computers, tablets, and smart phones gives customers 
          a trusted way to pay across different devices.</p>
      <p class="bg-info">To complete the demo, click the PayPal Checkout button and use the following credentials to login to PayPal.<br /><br />
      Email Address:  paypalphp@angelleye.com<br />
      Password:  paypalphp
      </p>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th class="center">Price</th>
            <th class="center">QTY</th>
            <th class="center">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
    foreach($_SESSION['shopping_cart']['items'] as $cart_item) {
        ?>
          <tr>
            <td><?php echo $cart_item['id']; ?></td>
            <td><?php echo $cart_item['name']; ?></td>
            <td class="center"> $<?php echo number_format($cart_item['price'],2); ?></td>
            <td class="center"><?php echo $cart_item['qty']; ?></td>
            <td class="center"> $<?php echo round($cart_item['qty'] * $cart_item['price'],2); ?></td>
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
                <td> $<?php echo number_format($_SESSION['shopping_cart']['subtotal'],2); ?></td>
              </tr>
              <tr>
                <td><strong>Shipping</strong></td>
                <td>$<?php echo number_format($_SESSION['shopping_cart']['shipping'],2); ?></td>
              </tr>
              <tr>
                <td><strong>Handling</strong></td>
                <td>$<?php echo number_format($_SESSION['shopping_cart']['handling'],2); ?></td>
              </tr>
              <tr>
                <td><strong>Tax</strong></td>
                <td>$<?php echo number_format($_SESSION['shopping_cart']['tax'],2); ?></td>
              </tr>
              <tr>
                <td><strong>Grand Total</strong></td>
                <td>$<?php echo number_format($_SESSION['shopping_cart']['grand_total'],2); ?></td>
              </tr>
              <tr>
              </tr>
            </tbody>
          </table>
            <form id="myContainer" method="post" action="SetExpressCheckout.php"></form> 
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<script>
   window.paypalCheckoutReady = function () {
     paypal.checkout.setup('<?php echo $api_username; ?>', {
         environment: 'sandbox',
         container: 'myContainer'
       });
  };
</script>

<script src="http://www.paypalobjects.com/api/checkout.js" async></script>