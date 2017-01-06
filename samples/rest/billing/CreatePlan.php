<?php

require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);

$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$plan= array(
  'Name'         => 'TJ T-Shirt of the Month Club Plan',                                     // Required. Name of the billing plan. 128 characters max.
  'Description'  => 'TJ Template creation.',                                     // Required. Description of the billing plan. 128 characters max.
  'Type'         => 'FIXED',                                     // Required.  Type of the billing plan. Allowed values: `FIXED`, `INFINITE`.
  'CreateTime'   => '',                                     // Time when the billing plan was created. Format YYYY-MM-DDTimeTimezone, as defined in [ISO8601](http://tools.ietf.org/html/rfc3339#section-5.6).  
);
// Payment Defination is Required for creating Plan. 
$paymentDefinition = array(
    'Name'              => 'TJ Regular Payments',                              // Name of the payment definition. 128 characters max.
    'Type'              => 'REGULAR',                              // Allowed values: `TRIAL`, `REGULAR`. Type of the payment definition.
    'FrequencyInterval' => '2',                              // How frequently the customer should be charged.
    'Frequency'         => 'MONTH',                              // Allowed values: `WEEK`, `DAY`, `YEAR`, `MONTH`. Frequency of the payment definition offered.
    'Cycles'            => '12',                              // Number of cycles in this payment definition.
    'Amount'            => array (
                            'value'    => '100',                   // Amount that will be charged at the end of each cycle for this payment definition.
                            'currency' => 'USD'                    // Three Letter Currency code.
                            )
    
); 

$chargeModel = array (
    'Type'   => 'SHIPPING',                                         // Allowed values: `SHIPPING`, `TAX`. Type of charge model.
    'Amount' => array(
                        'value'    => '10',                   // Amount to charge.
                        'currency' => 'USD'                    // Three Letter Currency code.
                    )
);

$baseUrl   = "http://localhost/paypal-php-library/samples/rest/billing/";
$CancelUrl = '/ExecuteAgreement.php?success=false';          // Redirect URL on cancellation of agreement request. 1000 characters max.
$ReturnUrl = '/ExecuteAgreement.php?success=true';           // Redirect URL on creation of agreement request. 1000 characters max.
// Merchant Preferences are optional for creating plan.
$merchant_preferences = array(   
    "NotifyUrl"               => '',                         // Notify URL on agreement creation. 1000 characters max.
    "MaxFailAttempts"         => '',                         // Total number of failed attempts allowed. Default is 0, representing an infinite number of failed attempts.
    "AutoBillAmount"          => 'yes',                         // Allowed values: `YES`, `NO`. Default is `NO`. Allow auto billing for the outstanding amount of the agreement in the next cycle.
    "InitialFailAmountAction" => 'CONTINUE',                         // Allowed values: `CONTINUE`, `CANCEL`. Default is continue. Action to take if a failure occurs during initial payment.
    "AcceptedPaymentType"     => '',                         // Payment types that are accepted for this plan.
    "SetupFee"                => array(
                                  'value'    => '1',          // Setup fee amount. Default is 0.
                                  'currency' => 'USD'           // Three Letter Currency code.
                                )
);

$requestData = array(
        "plan"                 => $plan,
        "paymentDefinition"    => $paymentDefinition,
        "chargeModel"          => $chargeModel,
        "merchant_preferences" => $merchant_preferences,
        "baseUrl"              => $baseUrl ,
        "CancelUrl"            => $CancelUrl,
        "ReturnUrl"            => $ReturnUrl
);

$returnArray = $PayPal->create_plan($requestData);
echo "<pre>";
var_dump($returnArray);
?>