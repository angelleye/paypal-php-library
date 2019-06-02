<?php

use \angelleye\PayPal\rest\checkout_orders\CheckoutOrdersAPI;

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

$PayPal = new CheckoutOrdersAPI($configArray);

/**
 * The intent to either capture payment immediately or authorize a payment for an order after order creation.
 * allowed values are: CAPTURE , AUTHORIZE
 * CAPTURE. The merchant intends to capture payment immediately after the customer makes a payment.
 * AUTHORIZE. The merchant intends to authorize a payment and place funds on hold after the customer makes a payment. Authorized payments are guaranteed for up to three days but are available to capture for up to 29 days. After the three-day honor period, the original authorized payment expires and you must re-authorize the payment. You must make a separate request to capture payments on demand.
 */

$intent = 'CAPTURE';            // Required

$currency = 'USD';              // The three-character ISO-4217 currency code that identifies the currency. https://developer.paypal.com/docs/integration/direct/rest/currency-codes/

$items[0] = array(
    'sku'         => '123',                                    // Stock keeping unit corresponding (SKU) to item.Maximum length: 127.
    'name'        => 'Hat',                                    // Required if you are adding item array. The item name or title. 127 characters max.
    'description' => 'Kansas City Chiefs Large Multi-Fit Hat', // The detailed item description. Maximum length: 127.
    'quantity'    => 1,                                        // The item quantity. Must be a whole number. Maximum length: 10.
    'unit_amount' => array(
        'value' => 7.50,
        'currency_code' => $currency
    ),                                     // Required if you are adding item array. The item price or rate per unit. 32 characters max.
    'tax'         => array(
        'value' => 0.00,
        'currency_code' => $currency
    ),                                     // The item tax for each unit.
    'category'    => 'PHYSICAL_GOODS'                          // The item category type. DIGITAL_GOODS | PHYSICAL_GOODS
);

$items[1] = array(
    'sku'         => '678',                                 // Stock keeping unit corresponding (SKU) to item.Maximum length: 127.
    'name'        => 'Handbag',                             // Required if you are adding item array. The item name or title. 127 characters max.
    'description' => 'Small, leather handbag.',             // The detailed item description. Maximum length: 127.
    'quantity'    => 2,                                     // The item quantity. Must be a whole number. Maximum length: 10.
    'unit_amount' => array(
        'value' => 5.00,
        'currency_code' => $currency
    ),                                  // Required if you are adding item array. The item price or rate per unit. 32 characters max.
    'tax'         => array(
        'value' => 0.00,
        'currency_code' => $currency
    ),                                  // The item tax for each unit.
    'category'    => 'PHYSICAL_GOODS'                       // The item category type. DIGITAL_GOODS | PHYSICAL_GOODS
);

$orderItems = $items;

$amount = array(
    'currency_code' => $currency,
    'value' => 17.50,
    'breakdown' => array(
        'item_total' => array(          // The subtotal for all items.
            'value' => 17.50,
            'currency_code' => $currency
        ),
        'shipping' => array(            // The shipping fee for all items.
            'value' => 0.00,
            'currency_code' => $currency
        ),
        'handling' => array(            // The handling fee for all items.
            'value' => 0.00,
            'currency_code' => $currency
        ),
        'tax_total' => array(            // The total tax for all items.
            'value' => 0.00,
            'currency_code' => $currency
        ),
        'insurance' => array(            // The insurance fee for all items.
            'value' => 0.00,
            'currency_code' => $currency
        ),
        'shipping_discount' => array(    // The shipping discount for all items.
            'value' => 0.00,
            'currency_code' => $currency
        )
    )
);

/**
 * For shipping_preferences
 * The possible values are:
 *   GET_FROM_FILE  -  Use the customer-provided shipping address on the PayPal site.
 *   NO_SHIPPING -  Redact the shipping address from the PayPal site. Recommended for digital goods.
 *   SET_PROVIDED_ADDRESS - Use the merchant-provided address. The customer cannot change this address on the PayPal site.
 *
 *   note : if you select SET_PROVIDED_ADDRESS then you should pass $shipping array that we have in sample as commented code
 *
 */

/**
 * For landing_page , The type of landing page to show on the PayPal site for customer checkout.
 * The possible values are :
 *       LOGIN -   Default. When the customer clicks PayPal Checkout, the customer is redirected to a page to log in to PayPal and approve the payment.
 *       BILLING - When the customer clicks PayPal Checkout, the customer is redirected to a page to enter credit or debit card and other relevant billing information required to complete the purchase.
 */

/**
 *  For user_action, Configures a Continue or Pay Now checkout flow.
 *  CONTINUE -  After you redirect the customer to the PayPal payment page, a Continue button appears. Use this option when the final amount is not known when the checkout flow is initiated and you want to redirect the customer to the merchant page without processing the payment.
 *  PAY_NOW - After you redirect the customer to the PayPal payment page, a Pay Now button appears. Use this option when the final amount is known when the checkout is initiated and you want to process the payment immediately when the customer clicks Pay Now.
 */

$application_context = array(
    'brand_name' => 'AngellEye INC',              // The label that overrides the business name in the PayPal account on the PayPal site.
    'locale' => 'en-US',                          // PayPal supports a five-character code.
    'landing_page' => 'LOGIN',                    // Allowed Values : LOGIN,BILLING
    'shipping_preferences' => 'GET_FROM_FILE',    // Allowed Values : GET_FROM_FILE , NO_SHIPPING , SET_PROVIDED_ADDRESS
    'user_action' => 'CONTINUE',                  // Configures a Continue or Pay Now checkout flow.
    'payment_method' => array(
        'payer_selected' => 'PAYPAL',                   // Values : PAYPAL,PAYPAL_CREDIT. The customer and merchant payment preferences.
        'payee_preferred' => 'UNRESTRICTED'             // Values : UNRESTRICTED , IMMEDIATE_PAYMENT_REQUIRED
    ),
    'return_url' => $domain.'samples/rest/checkout_orders/CaptureOrder.php?success=true',  // The URL where the customer is redirected after the customer approves the payment.
    'cancel_url' => $domain.'samples/rest/checkout_orders/CaptureOrder.php?success=false', // The URL where the customer is redirected after the customer cancels the payment.
);

/*
 *
 * Uncomment if you have shipping_preferences as SET_PROVIDED_ADDRESS in application context.
$shipping = array(
                'method' => 'United States Postal Service',
                'name' => array(
                    'full_name' => 'Test Buyer', // When the party is a person, the party's full name.
                    'prefix' => '',              // The prefix, or title, to the party's name.
                    'given_name' => '',          // When the party is a person, the party's given, or first, name.
                    'surname' => '',             // When the party is a person, the party's surname or family name.
                    'middle_name' => '',         // When the party is a person, the party's middle name.
                    'suffix' => ''               // The suffix for the party's name.
                ),
                'address' => array(
                    'address_line_1' => '123 Townsend St',
                    'address_line_2' => 'Floor 6',
                    'admin_area_2' => 'San Francisco',     // A city, town, or village.
                    'admin_area_1' => 'CA',                // country
                    'postal_code' => '94107',              // The postal code, which is the zip code or equivalent
                    'country_code' => 'US',                // The country code.
                ),
            );
*/

/*
 * Payer object is optional , if you have buyer details and you want to pass it then you can uncomment the code and use $payer in request
 *
$payer = array(             // The customer who approves and pays for the order. The customer is also known as the payer.
    'email_address' => 'test_buyer@domain.com', // The email address of the payer.
    'name' => array(
        'full_name' => 'Test Buyer', // When the party is a person, the party's full name.
        'prefix' => '',              // The prefix, or title, to the party's name.
        'given_name' => '',          // When the party is a person, the party's given, or first, name.
        'surname' => '',             // When the party is a person, the party's surname or family name.
        'middle_name' => '',         // When the party is a person, the party's middle name.
        'suffix' => ''               // The suffix for the party's name.
    ),
    'phone' => array(                // The phone number of the customer. Available only when you enable the Contact Telephone Number option in the Profile & Settings for the merchant's PayPal account.
        'phone_type' => '',          // The phone type. FAX, HOME, MOBILE, OTHER, PAGER.
        'phone_number' => array(
            'national_number' => '', // The phone number, in its canonical international E.164 numbering plan format. Supports only the national_number property.
        )
    ),
    'birth_date' => '',              // The birth date of the payer in YYYY-MM-DD format.
    'address' => array(
        'address_line_1' => '123 Townsend St',
        'address_line_2' => 'Floor 6',
        'admin_area_2' => 'San Francisco',     // A city, town, or village.
        'admin_area_1' => 'CA',                // country
        'postal_code' => '94107',              // The postal code, which is the zip code or equivalent
        'country_code' => 'US',                // The country code.
    )
);
*/

$purchase_units  = array(
    'reference_id' => 'default',                  // The ID for the purchase unit. Required for multiple purchase_units or if an order must be updated by using PATCH. If you omit the reference_id for an order with one purchase unit, PayPal sets the reference_id to default.
    'description' => 'Sporting Goods',            // The purchase description. Maximum length: 127.
    'custom_id' => 'CUST-PayPalFashions',         // The API caller-provided external ID. Used to reconcile client transactions with PayPal transactions. Appears in transaction and settlement reports but is not visible to the payer.
    'soft_descriptor' => 'PayPalFashions',        // The payment descriptor on the payer's credit card statement. Maximum length: 22.
    'invoice_id' => 'AEINV-'.rand(0,1000),        // The API caller-provided external invoice number for this order. Appears in both the payer's transaction history and the emails that the payer receives. Maximum length: 127.
    'amount' => $amount,
    //'shipping' => $shipping,                        // Add $shipping if you have shipping_preferences as SET_PROVIDED_ADDRESS in application context.
    'items' => $orderItems
);

$requestArray = array(
    'intent'=>$intent,
    'application_context' => $application_context,
    'purchase_units' => $purchase_units,
    //'payer' => $payer
);

$response = $PayPal->CreateOrder($requestArray);

echo "<pre>";
print_r($response);
exit;