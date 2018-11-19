<?php
// Include required library files.
require_once('../../vendor/autoload.php');
require_once('../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'LogResults' => $log_results, 
    'LogPath' => $log_path,
    'LogLevel' => $log_level  
);

$PayPal = new angelleye\PayPal\rest\payments\PaymentAPI($configArray);

$intent= $_SESSION['intent'];        //Allowed values: sale, authorize, order.Payment intent. Must be set to sale for immediate payment, authorize to authorize a payment for capture later, or order to create an order.

$urls= array(
    'ReturnUrl'   => 'ExecutePayment.php?success=true',                                    // Required when Pay using paypal. Example : ExecutePayment.php?success=true
    'CancelUrl'   => 'ExecutePayment.php?success=false',                                   // Required when Pay using paypal. Example : ExecutePayment.php?success=false
    'BaseUrl'     => 'http://localhost/paypal-php-library/demo/create-and-execute-payment-using-paypal-rest-api/'                                     // Required.
);

$invoiceNumber= $_SESSION['invoiceNumber'];
$NoteToPayer = $_SESSION['NoteToPayer'];
$orderItems = $_SESSION['items'];
$paymentDetails = $_SESSION['paymentDetails'];
$amount = $_SESSION['amount'];


$requestData = array(
    'intent'         => $intent,    
    'invoiceNumber'  => $invoiceNumber,
    'orderItems'     => $orderItems,
    'paymentDetails' => $paymentDetails,
    'amount'         => $amount,
    'urls'           => $urls,
    'NoteToPayer'    => $NoteToPayer
);

$returnArray = $PayPal->create_payment_with_paypal($requestData);
if($returnArray['RESULT'] == 'Success'){
    $approvalUrl = $returnArray['PAYMENT']['approvalUrl'];
    header('Location: ' . $approvalUrl);
}
else{
    /**
     * Error page redirection
     */
    echo "<pre>";
    var_dump($returnArray);
    exit;
}