<?php
// Include required library files.
require_once('../../../autoload.php');
require_once('../../../includes/config.php');

$configArray = array(
    'Sandbox' => $sandbox,
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret,
    'LogResults' => $log_results,
    'LogPath' => $log_path,
    'LogLevel' => $log_level
);

$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$plan = array(
    'Name' => '',                                   // Required. Name of the billing plan. 128 characters max.
    'Description' => '',                            // Required. Description of the billing plan. 128 characters max.
    'Type' => '',                                   // Required. Type of the billing plan. Allowed values: `FIXED`, `INFINITE`.
    'CreateTime' => '',                             // Time when the billing plan was created. Format YYYY-MM-DDTimeTimezone, as defined in [ISO8601](http://tools.ietf.org/html/rfc3339#section-5.6).
);
// Payment Definition is Required for creating Plan.
$paymentDefinitions[0] = array(
    'Name' => '',                                  // Name of the payment definition. 128 characters max.
    'Type' => '',                                  // Allowed values: `TRIAL`, `REGULAR`. Type of the payment definition.
    'FrequencyInterval' => '',                     // How frequently the customer should be charged.
    'Frequency' => '',                             // Allowed values: `WEEK`, `DAY`, `YEAR`, `MONTH`. Frequency of the payment definition offered.
    'Cycles' => '',                                // Number of cycles in this payment definition.
    'Amount' => array(
        'value' => '',                             // Amount that will be charged at the end of each cycle for this payment definition.
        'currency' => ''                           // Three Letter Currency code.
    ),
    'ChargeModels' => array(
        0 => array(
            'Type' => 'SHIPPING',                  // Allowed values: `SHIPPING`, `TAX`. Type of charge model.
            'Amount' => array(
                'value' => '',                     // Amount to charge.
                'currency' => ''                   // Three Letter Currency code.
            )
        ),
        1 => array(
            'Type' => 'TAX',                       // Allowed values: `SHIPPING`, `TAX`. Type of charge model.
            'Amount' => array(
                'value' => '',                     // Amount to charge.
                'currency' => ''                   // Three Letter Currency code.
            )
        )
    )
);

$paymentDefinitions[1] = array(
    'Name' => '',                                  // Name of the payment definition. 128 characters max.
    'Type' => '',                                  // Allowed values: `TRIAL`, `REGULAR`. Type of the payment definition.
    'FrequencyInterval' => '',                     // How frequently the customer should be charged.
    'Frequency' => '',                             // Allowed values: `WEEK`, `DAY`, `YEAR`, `MONTH`. Frequency of the payment definition offered.
    'Cycles' => '',                                // Number of cycles in this payment definition.
    'Amount' => array(
        'value' => '',                             // Amount that will be charged at the end of each cycle for this payment definition.
        'currency' => ''                           // Three Letter Currency code.
    ),
    'ChargeModels' => array(
        0 => array(
            'Type' => 'SHIPPING',                  // Allowed values: `SHIPPING`, `TAX`. Type of charge model.
            'Amount' => array(
                'value' => '',                     // Amount to charge.
                'currency' => ''                   // Three Letter Currency code.
            )
        ),
        1 => array(
            'Type' => 'TAX',                       // Allowed values: `SHIPPING`, `TAX`. Type of charge model.
            'Amount' => array(
                'value' => '',                     // Amount to charge.
                'currency' => ''                   // Three Letter Currency code.
            )
        )
    )
);

$baseUrl = '';                                     // Base URL of your directory.
$CancelUrl = '';                                   // Redirect URL on cancellation of agreement request. 1000 characters max.
$ReturnUrl = '';                                   // Redirect URL on creation of agreement request. 1000 characters max.
// Merchant Preferences are optional for creating plan.
$merchant_preferences = array(
    "NotifyUrl" => '',                             // Notify URL on agreement creation. 1000 characters max.
    "MaxFailAttempts" => '',                       // Total number of failed attempts allowed. Default is 0, representing an infinite number of failed attempts.
    "AutoBillAmount" => '',                        // Allowed values: `YES`, `NO`. Default is `NO`. Allow auto billing for the outstanding amount of the agreement in the next cycle.
    "InitialFailAmountAction" => '',               // Allowed values: `CONTINUE`, `CANCEL`. Default is continue. Action to take if a failure occurs during initial payment.
    "AcceptedPaymentType" => '',                   // Payment types that are accepted for this plan.
    "SetupFee" => array(
        'value' => '',                             // Setup fee amount. Default is 0.
        'currency' => ''                           // Three Letter Currency code.
    )
);

$requestData = array(
    "plan" => $plan,
    "paymentDefinition" => $paymentDefinitions,
    "merchant_preferences" => $merchant_preferences,
    "baseUrl" => $baseUrl,
    "CancelUrl" => $CancelUrl,
    "ReturnUrl" => $ReturnUrl
);

$returnArray = $PayPal->CreatePlan($requestData);
echo "<pre>";
print_r($returnArray);
