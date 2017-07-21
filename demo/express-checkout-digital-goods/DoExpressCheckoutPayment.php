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

/**
 * Now we'll setup the request params for the final call in the Express Checkout flow.
 * This is very similar to SetExpressCheckout except that now we can include values
 * for the shipping, handling, and tax amounts, as well as the buyer's name and
 * shipping address that we obtained in the GetExpressCheckoutDetails step.
 *
 * If this information is not included in this final call, it will not be
 * available in PayPal's transaction details data.
 *
 * Once again, the template for DoExpressCheckoutPayment provides
 * many more params that are available, but we've stripped everything
 * we are not using in this basic demo out.
 */
$DECPFields = array(
    'token' => $_SESSION['paypal_token'], 								// Required.  A timestamped token, the value of which was returned by a previous SetExpressCheckout call.
    'payerid' => $_SESSION['paypal_payer_id'], 							// Required.  Unique PayPal customer id of the payer.  Returned by GetExpressCheckoutDetails, or if you used SKIPDETAILS it's returned in the URL back to your RETURNURL.
);

/**
 * Just like with SetExpressCheckout, we need to gather our $Payment
 * data to pass into our $Payments array.  This time we can include
 * the shipping, handling, tax, and shipping address details that we
 * now have.
 */
$Payments = array();
$Payment = array(
    'amt' => number_format($_SESSION['shopping_cart']['grand_total'],2), 	    // Required.  The total cost of the transaction to the customer.  If shipping cost and tax charges are known, include them in this value.  If not, this value should be the current sub-total of the order.
    'itemamt' => number_format($_SESSION['shopping_cart']['subtotal'],2),       // Subtotal of items only.
    'currencycode' => 'USD', 					                                // A three-character currency code.  Default is USD.
    'paymentaction' => 'Sale', 					                                // How you want to obtain the payment.  When implementing parallel payments, this field is required and must be set to Order.
);

/**
 * Here we'll begin creating our order items that belong to this $Payment in the request.
 * We will loop through the items in our shopping cart to add them each into our
 * $Payment.
 *
 * We have added the "itemcategory" parameter here to notify the PayPal system
 * that this payment is for digital goods.
 */
$PaymentOrderItems = array();
foreach($_SESSION['shopping_cart']['items'] as $cart_item)
{
    $Item = array(
        'name' => $cart_item['name'], 							// Item name. 127 char max.
        'amt' => $cart_item['price'], 							// Cost of item.
        'number' => $cart_item['id'], 							// Item number.  127 char max.
        'qty' => $cart_item['qty'], 							// Item qty on order.  Any positive integer.
        'itemcategory' => 'Digital', 							// One of the following values:  Digital, Physical
    );
    array_push($PaymentOrderItems, $Item);
}

/**
 * Now that $PaymentOrderItems is filled with all of our shopping cart items,
 * we'll add that to our $Payment array.
 */
$Payment['order_items'] = $PaymentOrderItems;

/**
 * Here we push our single $Payment into our $Payments array.
 */
array_push($Payments, $Payment);

/**
 * Now we gather all of the arrays above into a single array.
 */
$PayPalRequestData = array(
					   'DECPFields' => $DECPFields, 
					   'Payments' => $Payments, 
					   );

/**
 * Here we are making the call to the DoExpressCheckoutPayment function in the library,
 * and we're passing in our $PayPalRequestData that we just set above.
 */
$PayPalResult = $PayPal->DoExpressCheckoutPayment($PayPalRequestData);

/**
 * Now we'll check for any errors returned by PayPal, and if we get an error,
 * we'll save the error details to a session and redirect the user to an
 * error page to display it accordingly.
 *
 * If the call is successful, we'll save some data we might want to use
 * later into session variables, and then redirect to our final
 * thank you / receipt page.
 */
if($PayPal->APICallSuccessful($PayPalResult['ACK']))
{
    /**
     * Once again, since Express Checkout allows for multiple payments in a single transaction,
     * the DoExpressCheckoutPayment response is setup to provide data for each potential payment.
     * As such, we need to loop through all the payment info in the response.
     *
     * The library helps us do this using the GetExpressCheckoutPaymentInfo() method.  We'll
     * load our $payments_info using that method, and then loop through the results to pull
     * out our details for the transaction.
     *
     * Again, in this case we are you only working with a single payment, but we'll still
     * loop through the results accordingly.
     *
     * Here, we're only pulling out the PayPal transaction ID and fee amount, but you may
     * refer to the API reference for all the additional parameters you have available at
     * this point.
     *
     * https://developer.paypal.com/docs/classic/api/merchant/DoExpressCheckoutPayment_API_Operation_NVP/
     */
    $payments_info = $PayPal->GetExpressCheckoutPaymentInfo($PayPalResult);

    foreach($payments_info as $payment_info)
    {
        $_SESSION['paypal_transaction_id'] = isset($payment_info['TRANSACTIONID']) ? $payment_info['TRANSACTIONID'] : '';
        $_SESSION['paypal_fee'] = isset($payment_info['FEEAMT']) ? $payment_info['FEEAMT'] : '';
    }
}
else
{
    $_SESSION['paypal_errors'] = $PayPalResult['ERRORS'];
    header('Location: ../error.php');
    exit();
}
?>
<?php
/**
 * Here we're simply generating a very basic result page that
 * will display a popup message notifying the user whether
 * the payment was successful or not, and will then close
 * the payment window, leaving the user right back where they
 * were prior to starting payment.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PayPal Express Checkout Digital Goods Demo | Completed Order | PHP Class Library | Angell EYE</title>
</head>

<body>
<?php
if($PayPal->APICallSuccessful($PayPalResult['ACK']))
{
    ?>
    <script>
        alert("Payment Successful");
    </script>
<?php
}
else
{
?>
    <script>
        alert("Payment failed");
    </script>
<?php
}
?>
<script>
    window.onload = function(){
        if(window.opener){
            window.close();
        }
        else{
            if(top.dg.isOpen() == true){
                top.dg.closeFlow();
                return true;
            }
        }
    };
</script>
</body>
</html>