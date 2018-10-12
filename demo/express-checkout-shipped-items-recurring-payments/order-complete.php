<?php
/**
 * Include our config file and the PayPal library.
 */
require_once('../../includes/config.php');
require_once('../../autoload.php');

/**
 * Setup configuration for the PayPal library using vars from the config file.
 * Then load the PayPal object into $PayPal
 */
$PayPalConfig = array(
    'Sandbox' => $sandbox,
    'APIUsername' => $api_username,
    'APIPassword' => $api_password,
    'APISignature' => $api_signature,
    'PrintHeaders' => $print_headers,
    'LogResults' => $log_results,
    'LogPath' => $log_path,
);
$PayPal = new angelleye\PayPal\PayPal($PayPalConfig);
?>
<html lang="en">
<head>
<meta charset="utf-8">
<title>PayPal Express Checkout Recurring Payments Profile Demo | Order Complete | PHP Class Library | Angell EYE</title>
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
      <h2 align="center">Payment Complete!</h2>
      <p class="bg-info">
      	We have now reached the final thank you / receipt page and the payment has been processed!  We have added the PayPal Transaction ID
          from the shipped items in the cart and the Profile ID from the subscription
        to the Billing Information, which was provided in the DoExpressCheckcoutPayment and CreateRecurringPaymentsProfile responses.
      </p>
      <?php
      /**
       * If any PayPal errors are present at this point we will display them
       * in a separate paragraph.
       */
      if(!empty($_SESSION['paypal_errors']))
      {
          echo '<p class="bg-info">' . $PayPal->DisplayErrors($_SESSION['paypal_errors']) . '</p>';
      }
      ?>
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
      <table class="table table-bordered">
          <thead>
            <tr>
                <th>Monthly Subscription</th>
                <th>Amount</th>
            </tr>
          </thead>
          <tbody>
              <tr>
                  <td><?php echo $_SESSION['shopping_cart']['subscription']['billing_period']; ?></td>
                  <td><?php echo "$".$_SESSION['shopping_cart']['subscription']['amount']; ?></td>
              </tr>
          </tbody>
      </table>
      <div class="row clearfix">
        <div class="col-md-4 column">
          <p><strong>Billing Information</strong></p>
          <p>
          	<?php
			echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '<br />' . 
			$_SESSION['email'] . '<br />'. 
			$_SESSION['phone_number'] . '<br />' . 
                        '<strong>Transaction ID : </strong>' .$_SESSION['paypal_transaction_id']. '<br/>'.
			'<strong>Recurring Payment Profile ID : </strong>' .$_SESSION['RecurringProfileId'];
			?>
          </p>
        </div>
        <div class="col-md-4 column">
          <p><strong>Shipping Information</strong></p>
          <p>
          	<?php 
			echo $_SESSION['shipping_name'] . '<br />' .
			$_SESSION['shipping_street'] . '<br />' .
			$_SESSION['shipping_city'] . ', ' . $_SESSION['shipping_state'] . '  ' . $_SESSION['shipping_zip'] . '<br />' . 
			$_SESSION['shipping_country_name']; 
			?>
          </p>
        </div>
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
                <td>$<?php echo number_format($_SESSION['shopping_cart']['grand_total'],2); ?> one time<br />$<?php echo number_format($_SESSION['shopping_cart']['subscription']['amount'], 2); ?> / mo until canceled</td>
            </tr>
              <tr>
                  <td class="center" colspan="2">&nbsp;</td>
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