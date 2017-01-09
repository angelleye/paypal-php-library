<html lang="en">
<head>
<meta charset="utf-8">
<title>PayPal PHP Class Library Demo | PayPal Partner | Angell EYE</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!--link rel="stylesheet/less" href="less/bootstrap.less" type="text/css" /-->
<!--link rel="stylesheet/less" href="less/responsive.less" type="text/css" /-->
<!--script src="/assets/js/less-1.3.3.min.js"></script-->
<!--append ‘#!watch’ to the browser URL, then refresh the page. -->

<link href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="assets/images/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="assets/images/favicon.png">
<script type="text/javascript" src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="assets/js/scripts.js"></script>
</head>

<body>
<div class="container">
  <div class="row clearfix">
    <div class="col-md-12 column">
      <div class="row clearfix">
        <div class="col-md-6 column">
          <div id="angelleye_logo"> <a href="/"><img alt="Angell EYE PayPal PHP Class Library Demo" src="assets/images/logo.png"></a> </div>
        </div>
        <div class="col-md-6 column">
          <div id="paypal_partner_logo"> <img alt="PayPal Partner and Certified Developer" src="assets/images/paypal-partner-logo.png" /> </div>
        </div>
      </div>
      <div class="jumbotron well">
        <h2> PayPal PHP Class Library Demo / Sample Code</h2>
        <p> This is a collection of small demo apps for a variety of PayPal checkout flows using our class library. </p>
        <p> <a class="btn btn-primary btn-large" href="https://github.com/angelleye/paypal-php-library" target="_blank">View PayPal Library on GitHub</a> </p>
      </div>
      <p class="bg-info"><strong>Sample Code Info</strong><br>
The sample code provided by the download buttons is pulled straight from this site. All paths to CSS, JS, etc. are setup relative to the site root, so if you are setting up a demo on your own server it is best to use a virtual host so that you can drop the demo files into the site root of your test server. We <a href="http://www.angelleye.com/product/training/">offer training for this sort of thing</a> if you are unsure how to handle this.</p>
      <div class="row">
        <div class="col-md-4">
          <div class="thumbnail"> <img alt="PayPal Express Checkout Basic Integration" src="assets/images/paypal-express-checkout.jpg">
            <div class="caption">
              <h3> Express Checkout </h3>
              <h4> Basic </h4>
              <p>Here we are integrating Express Checkout without any line item details or any extra features. We obtain the user's shipping information so that we can calculate shipping and tax, but otherwise no additional data is included with this checkout demo.</p>
              <p> <a class="btn btn-primary" href="express-checkout-basic/">Demo</a> <a class="btn btn-info" href="download/paypal-php-express-checkout-basic-demo.zip">Download Sample Code</a> </p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="thumbnail"> <img alt="PayPal Express Checkout Line Items Integration" src="assets/images/paypal-express-checkout-with-line-items.jpg">
            <div class="caption">
              <h3> Express Checkout </h3>
              <h4> w/ Line Items </h4>
              <p> Here we expand on our basic Express Checkout demo to add individual order items to the API requests so that the data is available within PayPal's checkout review pages transaction details. </p>
              <p> <a class="btn btn-primary" href="express-checkout-line-items/">Demo</a> <a class="btn btn-info" href="download/paypal-php-express-checkout-line-items-demo.zip">Download Sample Code</a> </p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="thumbnail"> <img alt="PayPal Express Checkout Digital Goods Integration" src="assets/images/paypal-express-checkout-digital-goods.jpg">
            <div class="caption">
              <h3> Express Checkout </h3>
              <h4> Digital Goods </h4>
              <p> Learn how to implement digital goods (micro-processing rates) into PayPal Express Checkout.  This includes the option for embedded payments. </p>
              <p> <a class="btn btn-primary" href="express-checkout-digital-goods/">Demo</a> <a class="btn btn-info" href="download/paypal-php-express-checkout-digital-goods-demo.zip">Download Sample Code</a> </p>
            </div>
          </div>
        </div>                   
      </div>
      <div class="row">
        <div class="col-md-4">
            <div class="thumbnail"> <img alt="Express Checkout Billing Agreement" src="assets/images/paypal-express-checkout-billing-agreement.jpg">
            <div class="caption">
              <h3> Express Checkout Billing Agreement </h3>
              <h4> Billing Agreement </h4>
              <p> Learn how to implement Billing Agreement into PayPal Express Checkout.  This includes the option for embedded payments. </p>
              <p> <a class="btn btn-primary" href="express-checkout-billing-agreement/">Demo</a> <a class="btn btn-info" href="download/paypal-php-express-checkout-digital-goods-demo.zip">Download Sample Code</a> </p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
            <div class="thumbnail"> <img alt="Express Checkout Parallel Payments" src="assets/images/paypal-express-parrallel-payments.jpg">
            <div class="caption">
              <h3> Express Checkout Parallel Payments </h3>
              <h4> Parallel Payments </h4>
              <p> Learn how to implement Parallel Payments into PayPal Express Checkout.  This includes the option for embedded payments. </p>
              <p> <a class="btn btn-primary" href="express-checkout-parallel-payments/">Demo</a> <a class="btn btn-info" href="download/paypal-php-express-checkout-digital-goods-demo.zip">Download Sample Code</a> </p>
            </div>
          </div>
        </div>      
        <div class="col-md-4">
            <div class="thumbnail"> <img alt="Express checkout 3rd Party No Permissions" src="assets/images/paypal-express-3rd-party-no-permissions-required.jpg">
            <div class="caption">
              <h3> Express checkout 3rd Party No Permissions </h3>
              <h4> 3rd Party No Permissions </h4>
              <p> Learn how to implement 3rd Party No Permissions into PayPal Express Checkout.  This includes the option for embedded payments. </p>
              <p> <a class="btn btn-primary" href="express-checkout-3rd-party-no-permissions/">Demo</a> <a class="btn btn-info" href="download/paypal-php-express-checkout-digital-goods-demo.zip">Download Sample Code</a> </p>
            </div>
          </div>
        </div> 
      </div>
      <div class="row">
        <div class="col-md-4">
            <div class="thumbnail"> <img alt="Website Payments Pro 30 Basic" src="assets/images/paypal-express-website-payments-pro-3-0.jpg">
            <div class="caption">
              <h3> Website Payments Pro 30 Basic </h3>
              <h4> Website Payments Pro 30 Basic </h4>
              <p> Learn how to implement Website Payments Pro 30 Basic.  This includes the option for embedded payments. </p>
              <p> <a class="btn btn-primary" href="website-payments-pro-30-basic/">Demo</a> <a class="btn btn-info" href="download/paypal-php-express-checkout-digital-goods-demo.zip">Download Sample Code</a> </p>
            </div>
          </div>
        </div>              
      </div> 
    </div>
  </div>
</div>
</body>
</html>