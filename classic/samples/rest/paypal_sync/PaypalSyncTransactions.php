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

$PayPal = new \angelleye\PayPal\rest\paypal_sync\PayPalSyncAPI($configArray);

$parameters = array(
    'transaction_id' => '61K22761BD5574252',                // Filters the transactions in the response by a PayPal transaction ID. A valid transaction ID is 17 characters long, except for an order ID, which is 19 characters long. Minimum length: 17.Maximum length: 19.
    'transaction_type' => '',                               // Filters the transactions in the response by a PayPal transaction event code. See Transaction event codes. https://developer.paypal.com/docs/integration/direct/sync/transaction-event-codes/
    'transaction_status' => '',                             // Filters the transactions in the response by a PayPal transaction status code. Value is: 
                                                            // D : PayPal or merchant rules denied the transaction.
                                                            // F : The original recipient partially refunded the transaction.
                                                            // P : The transaction is pending. The transaction was created but waits for another payment process to complete, such as an ACH transaction, before the status changes to S.
                                                            // S : The transaction successfully completed without a denial and after any pending statuses.
                                                            // V : A successful transaction was reversed and funds were refunded to the original sender.
    'transaction_amount' => '',                             // Filters the transactions in the response by a gross transaction amount range. Specify the range as <start-range> TO <end-range>, where <start-range> is the lower limit of the gross PayPal transaction amount and <end-range> is the upper limit of the gross transaction amount. Specify the amounts in lower denominations. For example, to search for transactions from $5.00 to $10.05, specify [500 TO 1005].
    'transaction_currency' => '',                           // PayPal transaction currency.
    'start_date' => '2018-03-01T00:00:00-0700',             // Example : 2017-07-01T00:00:00-0700 Filters the transactions in the response by a start date and time Minimum length: 20.Maximum length: 64. 
    'end_date' => '2018-03-30T00:00:00-0700',               // Example : 2017-07-30T00:00:00-0700 Filters the transactions in the response by a end date and time Minimum length: 20.Maximum length: 64. 
    'payment_instrument_type' => '',                        // CREDITCARD | DEBITCARD | Filters the transactions in the response by a payment instrument type, If you omit this parameter, the API does not apply this filter.
    'store_id'  => '' ,                                     // Filters the transactions in the response by a store ID.
    'terminal_id' => '',                                    // Filters the transactions in the response by a terminal ID.
    'fields' => 'all',                                      // Indicates which fields appear in the response. Value is a single field or a comma-separated list of fields. Other fields are : transaction_info | payer_info | shipping_info | auction_info | cart_info | incentive_info | store_info
    'balance_affecting_records_only' =>  'Y',               // Indicates whether the response includes only balance-impacting transactions or all transactions. Value is either: Y. The default. The response includes only balance transactions. N. The response includes all transactions.
    'page_size' => '',                                      // Minimum value: 1.  Maximum value: 500. The number of items to return in the response.   
    'page' => ''                                            // Minimum value: 1. Maximum value: 2147483647.                
);

$response = $PayPal->PaypalSyncTransactions($parameters);

echo "<pre>";
print_r($response);
exit;
