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

$PayPal = new \angelleye\PayPal\rest\referenced_payouts\ReferencedPayoutsAPI($configArray);

// Header parameters
$PayPal_Partner_Attribution_Id = 'AngellEYE_PHPClass'; // For more information about PayPal-Partner-Attribution-Id, see https://developer.paypal.com/docs/api/overview/#paypal-partner-attribution-id

/*  Indicates how the client expects the server to process this request.
 *  To process the request asynchronously, set this header to respond-async.
 *  If you omit this header, the API processes the request synchronously.
 *  For synchronous processing the application may levy additional checks on the number of supported items
 *  in the request and may fail the request if those limits are breached.
 */
$Prefer = '';
$PayPal_Request_Id = 'idempotent1234';          // The server stores keys for three days. For more information about PayPal-Request-Id, see https://developer.paypal.com/docs/api/overview/#paypal-request-id

if(!empty($PayPal_Partner_Attribution_Id)) {
    $PayPal->set_partner_attribution_id($PayPal_Partner_Attribution_Id);
}
if(!empty($Prefer)) {
    $PayPal->set_prefer($Prefer);
}
if(!empty($PayPal_Request_Id)) {
    $PayPal->set_paypal_request_id($PayPal_Request_Id);
}

$referenced_payouts_items = array();

$processing_state_1 = array(
    'status' => '',         // PENDING | PROCESSING | SUCCESS | FAILED | PAYOUT_FAILED | The item status.
    'reason' => ''          // INTERNAL_ERROR | NOT_ENOUGH_BALANCE | AMOUNT_CHECK_FAILED | MERCHANT_PARTNER_PERMISSIONS_ISSUE | MERCHANT_RESTRICTIONS | TRANSACTION_UNDER_DISPUTE |TRANSACTION_NOT_VALID | UNSUPPORTED_CURRENCY |PAYOUT_INITIATED | The reason code.
);

$payout_amount = array(
    'currency_code' => '',  // The three-character ISO-4217 currency code that identifies the currency.
    'value' => ''           // Maximum length: 32. | Pattern: ^((-?[0-9]+)|(-?([0-9]+)?[.][0-9]+))$
);

$referenced_payouts_item_1 = array(
    'item_id' => '',                        // The ID for the payout item request.
    'processing_state' => array_filter($processing_state_1),               // The processing state of the reference payout.
    'reference_id' => '1E9779735S6556259',                   // The original reference ID, based on reference_type, based on the type payout.
    'reference_type' => 'TRANSACTION_ID',                 // TRANSACTION_ID | OTHERS | The reference type.
    'payout_transaction_id' => '',          // The encrypted PayPal transaction ID for the payout when the item_status is success.
    'disbursement_transaction_id' => '',    // The encrypted PayPal transaction ID for the disbursement when the money is moved from settlement hold to receiver.
    'external_merchant_id' => '',           // The unique ID for the merchant on the partner side. Can be used to retrieve the PayPal account linked to this ID for the payout.
    'external_reference_id' => '',          // The unique ID for the request on the partner side to enable idempotency. If this parameter is not available, idempotency is enabled for this item.
    'payee_email' => '',                    // The PayPal merchant account email that receives the payout. Can be used to override the default behavior where the payout receiver is derived from the reference that is passed.
    'payout_amount' => array_filter($payout_amount),      // The amount to be paid to merchant. 
    'payout_destination' => '',             // The encrypted PayPal account number or the ID of the financial instrument that received the payout.
    'invoice_id' => '',                     // The partner invoice ID for this referenced-payouts item. Used for reporting purposes only.
    'custom' =>''                           // The partner custom data for this referenced-payouts item. Used for reporting purposes only.
);

array_push($referenced_payouts_items, array_filter($referenced_payouts_item_1));

$referenced_payouts_item_2 = array(
    'item_id' => '',                        // The ID for the payout item request.
    'processing_state' => array_filter($processing_state_1),               // The processing state of the reference payout.
    'reference_id' => '76F40949RG330734X',                   // The original reference ID, based on reference_type, based on the type payout.
    'reference_type' => 'TRANSACTION_ID',                 // TRANSACTION_ID | OTHERS | The reference type.
    'payout_transaction_id' => '',          // The encrypted PayPal transaction ID for the payout when the item_status is success.
    'disbursement_transaction_id' => '',    // The encrypted PayPal transaction ID for the disbursement when the money is moved from settlement hold to receiver.
    'external_merchant_id' => '',           // The unique ID for the merchant on the partner side. Can be used to retrieve the PayPal account linked to this ID for the payout.
    'external_reference_id' => '',          // The unique ID for the request on the partner side to enable idempotency. If this parameter is not available, idempotency is enabled for this item.
    'payee_email' => '',                    // The PayPal merchant account email that receives the payout. Can be used to override the default behavior where the payout receiver is derived from the reference that is passed.
    'payout_amount' => array_filter($payout_amount),      // The amount to be paid to merchant. 
    'payout_destination' => '',             // The encrypted PayPal account number or the ID of the financial instrument that received the payout.
    'invoice_id' => '',                     // The partner invoice ID for this referenced-payouts item. Used for reporting purposes only.
    'custom' =>''                           // The partner custom data for this referenced-payouts item. Used for reporting purposes only.
);

array_push($referenced_payouts_items, array_filter($referenced_payouts_item_2));

$referenced_payouts  = $referenced_payouts_items;

$payout_directive = array(
    'financial_instrument_id' => ''              // Minimum length: 1. Maximum length: 255. The PayPal-provided ID of the financial instrument that receives the payout. If you omit this value, the payout is made to the receiver's balance by default. If you include payee_email to override the original receiver on the individual item or items, the API ignores this field.
);

$parameters = array(    
    'referenced_payouts' => $referenced_payouts,         // An array of referenced payouts items. For synchronous execution, the maximum number of items is 10. If you include more than 10 items, the request is processed asynchronously no matter what the partner defined in the Prefer request header.
    'payout_directive' => array_filter($payout_directive)              // The payout directive. Defines how the payout is made following the referenced payouts, if required. If you include this directive, all items in the request must be for the same original receiver. Otherwise, the request fails. You can override the payout directive at the item level to a different funding instrument, if required.
);

$response = $PayPal->CreateReferencedBatchPayout($parameters);

echo "<pre>";
print_r($response);
exit;