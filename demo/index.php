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
                    <div id="angelleye_logo"><a href="/"><img alt="Angell EYE PayPal PHP Class Library Demo"
                                                              src="assets/images/logo.png"></a></div>
                </div>
                <div class="col-md-6 column">
                    <div id="paypal_partner_logo"><img alt="PayPal Partner and Certified Developer"
                                                       src="assets/images/paypal-partner-logo.png"/></div>
                </div>
            </div>
            <div class="jumbotron well">
                <h2> PayPal PHP Class Library Demo / Sample Code</h2>
                <p> This is a collection of small demo apps for a variety of PayPal checkout flows using our class
                    library. </p>
                <p><a class="btn btn-primary btn-large" href="https://github.com/angelleye/paypal-php-library"
                      target="_blank">View PayPal Library on GitHub</a></p>
            </div>
            <p class="bg-info"><strong>Demo Kit Info</strong><br>
                These demo kits offer a more complete demonstration of how the different checkout flows with PayPal
                work.
                Make sure to check /samples and /templates which are included free with the library and are often all
                you need
                to understand how things work. The samples are functional, but are only setup for one call at a time.
            </p>
            <p class="bg-info">The demo kits available here are more complete and tie together a number of APIs within a
                basic shopping cart interface so that you can see how to tie everything together.</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="PayPal Express Checkout Basic Integration"
                                                src="assets/images/paypal-express-checkout.jpg">
                        <div class="caption">
                            <h3>Express Checkout</h3>
                            <h4>Basic</h4>
                            <p>Here we are integrating Express Checkout without any line item details or any extra
                                features. We obtain the user's shipping information so that we can calculate shipping
                                and tax, but otherwise no additional data is included with this checkout demo.</p>
                            <p align="center"><a class="btn btn-primary" href="express-checkout-basic/">Launch Demo</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="PayPal Express Checkout Line Items Integration"
                                                src="assets/images/paypal-express-checkout-with-line-items.jpg">
                        <?php
                        $DIR = '\express-checkout-line-items';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3>Express Checkout</h3>
                            <h4>w/ Line Items</h4>
                            <p>Here we expand on our basic Express Checkout demo to add individual order items to the
                                API requests so that the data is available within PayPal's checkout review pages
                                transaction details.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="express-checkout-line-items/">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-line-items-demo/?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="PayPal Express Checkout Digital Goods Integration"
                                                src="assets/images/paypal-express-checkout-digital-goods.jpg">
                        <?php
                        $DIR = '\express-checkout-digital-goods';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3>Express Checkout</h3>
                            <h4>Digital Goods</h4>
                            <p>Learn how to implement digital goods (micro-processing rates) into PayPal Express
                                Checkout. This includes the option for embedded payments.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="express-checkout-digital-goods/">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-digital-goods-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Billing Agreement"
                                                src="assets/images/paypal-express-checkout-billing-agreement.jpg">
                        <?php
                        $DIR = '\express-checkout-billing-agreement';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3>Express Checkout Billing Agreement</h3>
                            <h4>Billing Agreement</h4>
                            <p>Learn how to implement Billing Agreement into PayPal Express Checkout. This includes the
                                option for embedded payments.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="express-checkout-billing-agreement/">Launch
                                        Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-billing-agreement-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Parallel Payments"
                                                src="assets/images/paypal-express-parrallel-payments.jpg">
                        <?php
                        $DIR = '\express-checkout-parallel-payments';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3>Express Checkout Parallel Payments</h3>
                            <h4>Parallel Payments</h4>
                            <p>Learn how to implement Parallel Payments into PayPal Express Checkout. This includes the
                                option for embedded payments.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="express-checkout-parallel-payments/">Launch
                                        Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-parallel-payments-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express checkout 3rd Party No Permissions"
                                                src="assets/images/paypal-express-3rd-party-no-permissions-required.jpg">
                        <?php
                        $DIR = '\express-checkout-3rd-party-no-permissions';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3>Express checkout 3rd Party No Permissions</h3>
                            <h4>3rd Party No Permissions</h4>
                            <p>Learn how to implement 3rd Party No Permissions into PayPal Express Checkout. This
                                includes the option for embedded payments.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="express-checkout-3rd-party-no-permissions/">Launch
                                        Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-3rd-party-no-permissions-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Website Payments Pro 30 Basic"
                                                src="assets/images/paypal-express-website-payments-pro-3-0.jpg">
                        <div class="caption">
                            <h3>Website Payments Pro 30 Basic</h3>
                            <h4>Website Payments Pro 30 Basic</h4>
                            <p>Learn how to implement Website Payments Pro 30 Basic. This includes the option for
                                embedded payments.</p>                            
                            <?php
                                $DIR = '\website-payments-pro-30-basic';
                                $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                            ?>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="ewebsite-payments-pro-30-basic/">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/website-payments-pro-30-basic-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout In-Context"
                                                src="assets/images/paypal-express-checkout-in-context.jpg">
                        <?php
                        $DIR = '\express-checkout-incontext';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3>Express Checkout In-Context</h3>
                            <h4>Express Checkout In-Context</h4>
                            <p>Learn how to implement Express Checkout In-Context so that users are not redirected away
                                from your site for payment.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="express-checkout-incontext/">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-in-context-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Recurring Payment"
                                                src="assets/images/express-checkout-recurring-payments.jpg">
                        <?php
                        $DIR = '\express-checkout-recurring-payments';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3>Express Checkout</h3>
                            <h4>Recurring Payments</h4>
                            <p>Learn how to implement Express Checkout - Recurring Payments.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="express-checkout-recurring-payments/">Launch
                                        Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-recurring-payments-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Shipped Items + Recurring Payment"
                                                src="assets/images/express-checkout-shipped-items-recurring-payments.png">
                        <?php
                        $DIR = '\express-checkout-shipped-items-recurring-payments';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3>Express Checkout</h3>
                            <h4>Shipped Items + Subscription</h4>
                            <p>Learn how to implement Express Checkout with shipped items and Subscription / Recurring
                                Payments together on a single order.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary"
                                       href="express-checkout-shipped-items-recurring-payments/">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-shipped-items-recurring-payments-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
</html>