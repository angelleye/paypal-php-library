<?php
require_once('../../../autoload.php');
require_once('../../../includes/config.php');
$configArray = array(
    'ClientID' => $rest_client_id,
    'ClientSecret' => $rest_client_secret
);
$PayPal = new angelleye\PayPal\rest\billing\BillingAPI($configArray);

$agreementId = 'I-C76T8XF96HBX';                                // Identifier of the agreement resource for which to list transactions.

$params = array(
                'start_date' => '2016-12-19',                    // Format : yyyy-mm-dd . The start date of the range of transactions to list.
                'end_date'   => '2016-12-20'                     // Format : yyyy-mm-dd . The end date of the range of transactions to list. 
          );

$returnArray = $PayPal->search_billing_transactions($agreementId,$params);
echo "<pre>";
var_dump($returnArray);
?>