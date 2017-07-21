<?php
require_once('../../includes/config.php');
require_once('../../autoload.php');

$PayPalConfig = array(
                    'Sandbox' => $sandbox,
                    'APIUsername' => $api_username,
                    'APIPassword' => $api_password,
                    'APISignature' => $api_signature, 
                    'PrintHeaders' => $print_headers,
                    'LogResults' => $log_results,
                    'LogPath' => $log_path,                 );

$PayPal = new angelleye\PayPal\PayPal($PayPalConfig);

$DPFields = array(
                    'paymentaction' => 'Sale',                      // How you want to obtain payment.  Authorization indicates the payment is a basic auth subject to settlement with Auth & Capture.  Sale indicates that this is a final sale for which you are requesting payment.  Default is Sale.
                    'ipaddress' => $_SERVER['REMOTE_ADDR'],                             // Required.  IP address of the payer's browser.
                    'returnfmfdetails' => '1'                   // Flag to determine whether you want the results returned by FMF.  1 or 0.  Default is 0.
                );
$creditcard_init_digit = substr(trim($_POST['paypal_dodirectpayment-card-number']),0,1); 
switch ($creditcard_init_digit) 
{
    case 3:
        $creditcardtype = 'Amex';
        break;
    case 4:
        $creditcardtype = 'Visa';
        break;
    case 5:
        $creditcardtype = 'MasterCard';
        break;
    case 6:
        $creditcardtype = 'Discover';
        break;
    default:
        $creditcardtype = '';
        break;
}               
$CCDetails = array(
                    'creditcardtype' => $creditcardtype,                   // Required. Type of credit card.  Visa, MasterCard, Discover, Amex, Maestro, Solo.  If Maestro or Solo, the currency code must be GBP.  In addition, either start date or issue number must be specified.
                    'acct' => $_POST['paypal_dodirectpayment-card-number'],                               // Required.  Credit card number.  No spaces or punctuation.  
                    'expdate' => $_POST['paypal_dodirectpayment-expiry-month'] . $_POST['paypal_dodirectpayment-expiry-year'],                          // Required.  Credit card expiration date.  Format is MMYYYY
                    'cvv2' => $_POST['paypal_dodirectpayment-cvv'],                                // Requirements determined by your PayPal account settings.  Security digits for credit card.
                    'startdate' => '',                          // Month and year that Maestro or Solo card was issued.  MMYYYY
                    'issuenumber' => ''                         // Issue number of Maestro or Solo card.  Two numeric digits max.
                );
                
$PayerInfo = array(
                    'email' => $_POST['billing_email'],                                 // Email address of payer.
                    'payerid' => '',                            // Unique PayPal customer ID for payer.
                    'payerstatus' => '',                        // Status of payer.  Values are verified or unverified
                    'business' => ''                            // Payer's business name.
                );
                
$PayerName = array(
                    'salutation' => '',                         // Payer's salutation.  20 char max.
                    'firstname' => $_POST['billing_first_name'],                            // Payer's first name.  25 char max.
                    'middlename' => '',                         // Payer's middle name.  25 char max.
                    'lastname' => $_POST['billing_last_name'],                          // Payer's last name.  25 char max.
                    'suffix' => ''                              // Payer's suffix.  12 char max.
                );
                
$BillingAddress = array(
                        'street' => $_POST['billing_address_1'],                         // Required.  First street address.
                        'street2' => '',                        // Second street address.
                        'city' => $_POST['billing_city'],                          // Required.  Name of City.
                        'state' => $_POST['billing_state'],                            // Required. Name of State or Province.
                        'countrycode' => $_POST['billing_country'],                  // Required.  Country code.
                        'zip' => $_POST['billing_postcode'],                           // Required.  Postal code of payer.
                        'phonenum' => $_POST['billing_phone']                        // Phone Number of payer.  20 char max.
                    );
                    
$ShippingAddress = array(
                        'shiptoname' => '',                     // Required if shipping is included.  Person's name associated with this address.  32 char max.
                        'shiptostreet' => '',                   // Required if shipping is included.  First street address.  100 char max.
                        'shiptostreet2' => '',                  // Second street address.  100 char max.
                        'shiptocity' => '',                     // Required if shipping is included.  Name of city.  40 char max.
                        'shiptostate' => '',                    // Required if shipping is included.  Name of state or province.  40 char max.
                        'shiptozip' => '',                      // Required if shipping is included.  Postal code of shipping address.  20 char max.
                        'shiptocountrycode' => '',              // Required if shipping is included.  Country code of shipping address.  2 char max.
                        'shiptophonenum' => ''                  // Phone number for shipping address.  20 char max.
                        );
                    
$PaymentDetails = array(
                        'amt' => $_SESSION['shopping_cart']['subtotal'],                            // Required.  Total amount of order, including shipping, handling, and tax.  
                        'currencycode' => 'USD',                    // Required.  Three-letter currency code.  Default is USD.
                        'itemamt' => '',                        // Required if you include itemized cart details. (L_AMTn, etc.)  Subtotal of items not including S&H, or tax.
                        'shippingamt' => $_SESSION['shopping_cart']['shipping'],                    // Total shipping costs for the order.  If you specify shippingamt, you must also specify itemamt.
                        'handlingamt' => $_SESSION['shopping_cart']['handling'],                    // Total handling costs for the order.  If you specify handlingamt, you must also specify itemamt.
                        'taxamt' => $_SESSION['shopping_cart']['tax'],                         // Required if you specify itemized cart tax details. Sum of tax for all items on the order.  Total sales tax. 
                        'desc' => '',                            // Description of the order the customer is purchasing.  127 char max.
                        'custom' => isset($_POST['custom'])?$_POST['custom']:"",                         // Free-form field for your own use.  256 char max.
                        'invnum' => isset($_POST['invnum'])?$_POST['invnum']:"",                         // Your own invoice or tracking number
                        'buttonsource' => '',                   // An ID code for use by 3rd party apps to identify transactions.
                        'notifyurl' => ''                       // URL for receiving Instant Payment Notifications.  This overrides what your profile is set to use.
                    );

$PayPalRequestData = array(
                           'DPFields' => $DPFields, 
                           'CCDetails' => $CCDetails, 
                           'PayerInfo' => $PayerInfo,
                           'PayerName' => $PayerName, 
                           'BillingAddress' => $BillingAddress, 
                           'PaymentDetails' => $PaymentDetails
                           );

$PayPalResult = $PayPal -> DoDirectPayment($PayPalRequestData);

//$_SESSION['transaction_id'] = isset($PayPalResult['TRANSACTIONID']) ? $PayPalResult['TRANSACTIONID'] : '';

$error = array();
if($PayPal->APICallSuccessful($PayPalResult['ACK']))
{    
    foreach ($_POST as $key => $value) {
        $_SESSION[$key] = $value;
    }
    $_SESSION['paypal_transaction_id'] = $PayPalResult['TRANSACTIONID'];
    header('Location: order-complete.php');
}
else{
    $error[] =  array("L_ERRORCODE"=>$PayPalResult['L_ERRORCODE0'],"L_LONGMESSAGE"=>$PayPalResult['L_LONGMESSAGE0']);
    $_SESSION['paypal_errors'] = $error;
    header('Location: ../error.php');
}