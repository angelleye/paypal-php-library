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
                <h2> PayPal PHP Class Library Demo Kits</h2>
                <p>Fully functional API calls built into an HTML experience for demonstration / training purposes.</p>
                <p><a class="btn btn-primary btn-large"
                      href="https://www.angelleye.com/product-category/php-class-libraries/demo-kits/"
                      target="_blank">Browse Our Demo Kits</a></p>
            </div>
            <p class="bg-info"><strong>What is This?</strong><br>
                The class library comes with FREE, fully functional /samples and empty /templates ready for you to work
                with. The demo kits
                available here are more complete and tie together a number of APIs within a basic shopping cart
                interface so that you can see how to tie everything together.</p>
            <p class="bg-info"><strong>How Does This Work?</strong><br>
                Any installed demo kits (some are included free) will display a Launch button. Simply click this to
                launch the demo
                and go through the experience to see how it works. The HTML and code has lots of comments explaining
                what is going
                on so that you can see it for yourself, and learn from it.<br/><br/>Additional demo kits are available
                for purchase
                and can be installed here to complete your collection.
            </p>
            <div class="row">
                <div class="col-lg-12">
                    <hr>
                    <h1>REST API</h1>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Basic"
                                                src="assets/images/paypal-express-checkout-with-line-items.jpg">
                        <div class="caption">
                            <h3>Express Checkout</h3>
                            <h4>w/ Line Items</h4>
                            <p>Learn how to integrate Express Checkout w/ Line Items using PayPal REST API. It allows to create and execute payment using REST API. This includes the option for
                                embedded payments.</p>
                            <?php
                            $DIR = '\rest-checkout-line-items';
                            $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                            ?>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="rest-checkout-line-items" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-rest-checkout-line-items-php-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Smart Payment Buttons Client Side"
                                                src="assets/images/paypal-express-checkout-jsv4-using-rest-client-side.jpg">
                        <?php
                        $DIR = '\express-checkout-js-using-rest';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> Express Checkout Smart Payment Buttons Client Side </h3>
                            <p>Learn how to integrate PayPal Checkout use the checkout.js JavaScript code. This code
                                always keeps you current with the latest button styles and payment features.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="demo/express-checkout-js-using-rest/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-smart-payment-buttons-client-side-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Smart Payment Buttons Server Side"
                                                src="assets/images/paypal-express-checkout-jsv4-using-rest-server-side.jpg">
                        <?php
                        $DIR = '\express-checkout-js-using-server-side';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> Express Checkout Smart Payment Buttons Server Side </h3>
                            <h4> Express Checkout Smart Payment Buttons Server Side </h4>
                            <p>Learn how to integrate PayPal Checkout use the checkout.js JavaScript code. This code
                                always keeps you current with the latest button styles and payment features.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="demo/express-checkout-js-using-rest/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-smart-payment-buttons-server-side-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">

                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Smart Payment Buttons Server Side"
                                                src="assets/images/paypal-express-checkout-billing-agreement.jpg">
                        <?php
                        $DIR = '\express-checkout-js-using-server-side';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> Express Checkout Billing Agreement</h3>
                            <h4> Using PayPal payment</h4>
                            <p>Learn how to Use billing plans and billing agreements to create an agreement for a recurring PayPal payment for goods or services.  This includes the option for embedded payments.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="demo/create-billing-agreement-using-paypal-rest-api/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-billing-agreement-rest-php-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="thumbnail"><img alt="PayPal Identity (Log In with PayPal)" src="assets/images/login-with-paypal.png">
                        <?php
                        $DIR = '\login-with-paypal-basic';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> PayPal Identity (Log In with PayPal) </h3>
                            <h4> Log In with PayPal with basic scope </h4>
                            <p> Log In with PayPal (formerly PayPal Access) is a commerce identity solution that enables
                                your customers to sign in to your web site quickly and securely by using their PayPal
                                login credentials.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="demo/login-with-paypal-basic/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-identity-paypal-login-php-basic-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="PayPal Identity + Grant API Permissions" src="assets/images/login-with-paypal.png">
                        <?php
                        $DIR = '\login-with-paypal-permissions';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> PayPal Identity + Grant API Permissions </h3>
                            <h4> PayPal Identity + Grant API Permissions</h4>
                            <p> Log In with PayPal (formerly PayPal Access) is a commerce identity solution that enables
                                your customers to sign in to your web site quickly and securely by using their PayPal
                                login credentials.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="demo/login-with-paypal-permissions/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-identity-paypal-login-php-grant-api-permissions-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Smart Payment Buttons Server Side"
                                                src="assets/images/paypal-dodirectpayment-credit-cart-checkout.jpg">
                        <?php
                        $DIR = '\create-payment-using-credit-card-rest-api';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> Create Payment using CreditCard</h3>
                            <h4> CreditCard Payment using REST API </h4>
                            <p>Learn how to create payment using CreditCard payment method. Amount directly deducted from the CC and transfared to PayPal account of merchant.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="demo/create-payment-using-credit-card-rest-api/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-create-payment-using-credit-rest-php-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Store CreditCard in PayPal Vault"
                                                src="assets/images/paypal-store-creditcard-in-paypal-vault.jpg">
                        <?php
                        $DIR = '\store-credit-card-in-paypal-vault';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> Store CreditCard in PayPal Vault</h3>
                            <h4>  </h4>
                            <p>Learn how to  use the Vault API to securely store customer credit cards in the PayPal vault rather than on your server. Demo includes pre filled form with the test card and integration with PayPal vault API. </p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="demo/store-credit-card-in-paypal-vault/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-store-credit-card-in-vault-rest-php-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Create Payment Using Saved Card - Vault"
                                                src="assets/images/paypal-create-payment-using-savedcard-vault.jpg">
                        <?php
                        $DIR = '\create-payment-using-saved-card';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> Create Payment Using Saved Card - Vault</h3>
                            <h4> Pay with vaulted card</h4>
                            <p>Learn how to implement payment system using the saved card in PayPal. To pay with a vaulted card, include the id returned in the <a href="demo/store-credit-card-in-paypal-vault/">store credit card response</a> as the credit card ID in a create payment call.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="demo/create-payment-using-saved-card/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-create-payment-using-credit-rest-php-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Create & Send Third Party Invoice PayPal"
                                                src="assets/images/paypal-create-send-third-party-invoice.jpg">
                        <div class="caption">
                            <h3>Create & Send Third Party Invoice PayPal </h3>
                            <h4>Invoicing API</h4>
                            <p>Learn how to create and send third party invoice in PayPal using  Invoicing API.</p>
                            <?php
                            $DIR = '\create-and-send-third-party-invoice-paypal';
                            $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                            ?>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="create-and-send-third-party-invoice-paypal/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-create-and-send-third-party-invoice-paypal-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <h1>Classic API</h1>
                    <hr>
                </div>
            </div>
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
                            <p align="center"><a class="btn btn-primary" href="express-checkout-basic/" target="_blank">Launch Demo</a>
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
                                    <a class="btn btn-primary" href="express-checkout-line-items/" target="_blank">Launch Demo</a>
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
                                    <a class="btn btn-primary" href="express-checkout-digital-goods/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-digital-goods-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
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
                                    <a class="btn btn-primary" href="express-checkout-billing-agreement/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-billing-agreement-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
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
                                    <a class="btn btn-primary" href="express-checkout-parallel-payments/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-parallel-payments-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
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
                                    <a class="btn btn-primary" href="express-checkout-3rd-party-no-permissions/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-3rd-party-payments-no-permissions-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
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
                                    <a class="btn btn-primary" href="express-checkout-incontext/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-in-context-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
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
                                    <a class="btn btn-primary" href="express-checkout-recurring-payments/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-recurring-payments-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Express Checkout Shipped Items + Recurring Payment"
                                                src="assets/images/express-checkout-shipped-items-recurring-payments.jpg">
                        <?php
                        $DIR = '\express-checkout-shipped-items-recurring-payments';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3>Express Checkout</h3>
                            <h4>Shipped Items + Subscription</h4>
                            <p>Learn how to implement Express Checkout with shipped items and Subscription / Recurring
                                Pay ments together on a single order.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary"
                                       href="express-checkout-shipped-items-recurring-payments/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-php-recurring-payments-shipped-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="PayPal Payments Pro PayFlow Basic Integration"
                                                src="assets/images/paypal-payments-pro-payflow.jpg">
                        <?php
                        $DIR = '\payflow-credit-card-checkout-basic';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> Payments Pro 2.0</h3>
                            <h4> PayFlow Basic </h4>
                            <p>See how to integrate credit card processing directly on your site using PayPal's PayFlow
                                gateway. This allows users to enter credit card details directly on your site without
                                any redirect to PayPal at all.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="payflow-credit-card-checkout-basic/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-payments-pro-payflow-php-basic-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="PayPal Payments Pro PayFlow Recurring Billing Integration"
                                                src="assets/images/paypal-payments-pro-payflow-recurring-billing.jpg">
                        <?php
                        $DIR = '\payflow-credit-card-checkout-recurring-billing';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> Payments Pro 2.0 </h3>
                            <h4> PayFlow Recurring Billing </h4>
                            <p>Here we use the PayFlow gateway to setup recurring billing / subscription profiles. This
                                is done using a credit card directly just like the Payments Pro basic demos.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="payflow-credit-card-checkout-recurring-billing/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-payments-pro-payflow-php-recurring-billing-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="thumbnail">
                        <img alt="Express Checkout Billing Agreement"
                             src="assets/images/paypal-dodirectpayment-credit-cart-checkout.jpg"/>
                        <?php
                        $DIR = '\dodirectpayment-credit-cart-checkout';
                        $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                        ?>
                        <div class="caption">
                            <h3> Direct Payment Cart Checkout </h3>
                            <h4> DoDirectPayment </h4>
                            <p>Use the Direct Payment API to accept direct credit card payments on your website from
                                buyers who do not have a PayPal account. PayPal processes the payment in the
                                background.</p>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="demo/dodirectpayment-credit-cart-checkout/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-website-payments-pro-php-dodirectpayment-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
                                       target="_blank">Buy Now</a>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="thumbnail"><img alt="Website Payments Pro 3.0 Basic"
                                                src="assets/images/paypal-express-website-payments-pro-3-0.jpg">
                        <div class="caption">
                            <h3>Website Payments Pro 3.0 Basic</h3>
                            <h4>Website Payments Pro 3.0 Basic</h4>
                            <p>Learn how to implement Website Payments Pro 30 Basic. This includes the option for
                                embedded payments.</p>
                            <?php
                            $DIR = '\website-payments-pro-30-basic';
                            $DIR_exists = (is_dir(__DIR__ . $DIR)) ? true : false;
                            ?>
                            <p align="center">
                                <?php if ($DIR_exists) : ?>
                                    <a class="btn btn-primary" href="website-payments-pro-30-basic/" target="_blank">Launch Demo</a>
                                <?php else: ?>
                                    <a class="btn btn-success"
                                       href="https://www.angelleye.com/product/paypal-express-checkout-website-payments-pro-dodirectpayment-demo-kit?utm_source=ae_paypal_php_sdk&utm_medium=demo_homepage&utm_campaign=demo_kits"
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