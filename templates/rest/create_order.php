<?php
// Include required library files
require_once('../../includes/config.php');
require_once('../../autoload.php');

$paypal_config = array(
    'sandbox' => $sandbox,
    'rest_client_id' => $rest_client_id,
    'rest_client_secret' => $rest_client_secret,
    'PrintHeaders' => $print_headers
);

try {
    // Create a new PayPal Rest object
    $paypal = new angelleye\PayPal\Rest($paypal_config);

    /**
     * Prepare Purchase Units with empty values
     */
    $purchase_units = array();

    $purchase_unit = [
        'reference_id' => '',                // string [1..256] - External ID for the purchase unit.
        'description' => '',                 // string [1..127] - The purchase description.
        'custom_id' => '',                   // string [1..255] - External ID for reconciliation.
        'invoice_id' => '',                  // string [1..127] - External invoice number.
        'soft_descriptor' => '',             // string [1..22] - Dynamic text for statement descriptor.

        // Amount object (required)
        'amount' => [
            'currency_code' => '',            // string [3] - The currency code for the payment.
            'value' => '',                    // string [1..32] - The total amount value.
            'breakdown' => [
                'item_total' => [             // Total item amount.
                    'currency_code' => '',    // string [3] - Currency code for items.
                    'value' => ''             // string [1..32] - Value of item total.
                ],
                'shipping' => [               // Shipping fee.
                    'currency_code' => '',    // string [3] - Currency code for shipping.
                    'value' => ''             // string [1..32] - Value of shipping.
                ],
                'handling' => [               // Handling fee.
                    'currency_code' => '',    // string [3] - Currency code for handling.
                    'value' => ''             // string [1..32] - Value of handling.
                ],
                'tax_total' => [              // Total tax amount.
                    'currency_code' => '',    // string [3] - Currency code for tax.
                    'value' => ''             // string [1..32] - Value of total taxes.
                ],
                'insurance' => [              // Insurance fee.
                    'currency_code' => '',    // string [3] - Currency code for insurance.
                    'value' => ''             // string [1..32] - Value of insurance.
                ],
                'shipping_discount' => [      // Shipping discount.
                    'currency_code' => '',    // string [3] - Currency code for discount.
                    'value' => ''             // string [1..32] - Value of shipping discount.
                ],
                'discount' => [               // General discount.
                    'currency_code' => '',    // string [3] - Currency code for discount.
                    'value' => ''             // string [1..32] - Value of discount.
                ]
            ]
        ],

        // Items array - Array of purchased items
        'items' => [
            [
                'name' => '',                  // required - string [1..127] - The item name or title.
                'quantity' => '',              // required - string [<=10] - The item quantity.
                'description' => '',           // string [<=127] - Detailed item description.
                'sku' => '',                   // string [<=127] - Stock keeping unit (SKU) for the item.
                'url' => '',                   // string [1..2048] - The URL to the item.
                'category' => '',              // string [1..20] - The item category type.
                'image_url' => '',             // string [1..2048] - URL to the item's image.
                'unit_amount' => [             // required - The item price or rate per unit.
                    'currency_code' => '',     // string [3] - Currency code for unit amount.
                    'value' => ''              // string [1..32] - Value of the unit amount.
                ],
                'tax' => [                     // Tax for each unit.
                    'currency_code' => '',     // string [3] - Currency code for taxes.
                    'value' => ''              // string [1..32] - Value of tax.
                ]
            ]
        ],

        // Payee object (optional)
        'payee' => [
            'email_address' => ''             // string [3..254] - Optional - Payee email address.
        ]
    ];

    // Push the purchase unit into the array
    array_push($purchase_units, $purchase_unit);

    /**
     * Prepare Payment Source data with empty values
     */
    $payment_source = [
        'card' => [
            'name' => '',                        // string [1..300] - The cardholder's name.
            'number' => '',                      // string [13..19] - The card number.
            'security_code' => '',               // string [3..4] - The card's security code (CVV, CVC).
            'expiry' => '',                      // string = 7 - Card expiration in YYYY-MM format.
            'billing_address' => [
                'address_line_1' => '',          // string [<=300] - First line of the billing address.
                'address_line_2' => '',          // string [<=300] - Second line of the billing address.
                'admin_area_1' => '',            // string [<=300] - State or province.
                'admin_area_2' => '',            // string [<=120] - City, town, or village.
                'postal_code' => '',             // string [<=60] - Postal or ZIP code.
                'country_code' => ''             // string = 2 - ISO 3166-1 country code.
            ],

            'attributes' => [
                'customer' => [
                    'id' => '',                   // string [1..22] - PayPal-generated customer ID.
                    'email_address' => ''          // string [3..254] - Customer's email address.
                ],
                'vault' => [
                    'store_in_vault' => ''         // string [1..255] - Instruction to vault the card (e.g., ON_SUCCESS).
                ],
                'verification' => [
                    'strategy' => ''               // Optional verification strategy for the card.
                ]
            ],

            'stored_credential' => [
                'payment_initiator' => '',        // string [1..255] - Initiator of the payment (CUSTOMER, MERCHANT).
                'payment_type' => '',             // string [1..255] - Type of payment (ONE_TIME, RECURRING, UNSCHEDULED).
                'usage' => '',                    // string [1..255] - Usage type (FIRST, SUBSEQUENT, DERIVED).
                'previous_network_transaction_reference' => [
                    'id' => '',                   // string [9..36] - Transaction reference ID returned by the scheme.
                    'date' => '',                 // string = 4 - Date the transaction was authorized by the scheme.
                    'acquirer_reference_number' => '', // string [1..36] - Acquirer reference number for the card transaction.
                    'network' => ''               // string [1..255] - Card network (VISA, MASTERCARD, etc.).
                ]
            ],

            'network_token' => [
                'number' => '',                    // string [13..19] - Network token number.
                'cryptogram' => '',                // string [28..32] - Encrypted one-time use value sent with network token.
                'token_requestor_id' => '',        // string [1..11] - Token Requestor ID (TRID) used to request the network token.
                'expiry' => '',                    // string = 7 - Token expiration in YYYY-MM format.
                'eci_flag' => ''                   // string [1..255] - Electronic Commerce Indicator (ECI) for transaction.
            ]
        ],
        'paypal' => [
            'experience_context' => [
                'brand_name' => '',            // string [1..127] - Overrides the business name.
                'shipping_preference' => '',   // string [1..24] - Shipping preference (GET_FROM_FILE, etc.).
                'landing_page' => '',          // string [1..13] - Type of landing page (LOGIN, GUEST_CHECKOUT, etc.).
                'user_action' => '',           // string [1..8] - Checkout flow (CONTINUE, PAY_NOW).
                'payment_method_preference' => '',  // string [1..255] - Merchant-preferred payment methods.
                'locale' => '',                // string [2..10] - The locale for PayPal pages (e.g., en_US).
                'return_url' => '',            // string - URL where the customer will be redirected after approval.
                'cancel_url' => '',            // string - URL where the customer will be redirected if they cancel.
            ]
        ],
        'bancontact' => [
            'name' => '',                      // required - string [3..300] - Name of the account holder.
            'country_code' => '',              // required - string [2] - The two-character country code.
            'experience_context' => [
                'brand_name' => '',            // string [1..127] - Overrides the business name.
                'shipping_preference' => '',   // string [1..24] - Shipping preference.
                'locale' => '',                // string [2..10] - The locale for PayPal pages.
                'return_url' => '',            // string - URL where the customer will be redirected after approval.
                'cancel_url' => ''             // string - URL where the customer will be redirected if they cancel.
            ]
        ],
        'blik' => [
            'name' => '',                      // required - string [3..300] - Name of the account holder.
            'country_code' => '',              // required - string [2] - The two-character country code.
            'email' => '',                     // string [3..254] - Email address of the account holder.
            'experience_context' => [
                'brand_name' => '',            // string [1..127] - Overrides the business name.
                'shipping_preference' => '',   // string [1..24] - Shipping preference.
                'locale' => '',                // string [2..10] - The locale for PayPal pages.
                'return_url' => '',            // string - URL where the customer will be redirected after approval.
                'cancel_url' => ''             // string - URL where the customer will be redirected if they cancel.
            ]
        ],
        'eps' => [
            'name' => '',                      // required - string [3..300] - Name of the account holder.
            'country_code' => '',              // required - string [2] - The two-character country code.
            'experience_context' => [
                'brand_name' => '',            // string [1..127] - Overrides the business name.
                'shipping_preference' => '',   // string [1..24] - Shipping preference.
                'locale' => '',                // string [2..10] - The locale for PayPal pages.
                'return_url' => '',            // string - URL where the customer will be redirected after approval.
                'cancel_url' => ''             // string - URL where the customer will be redirected if they cancel.
            ]
        ],
        'giropay' => [
            'name' => '',                      // required - string [3..300] - Name of the account holder.
            'country_code' => '',              // required - string [2] - The two-character country code.
            'experience_context' => [
                'brand_name' => '',            // string [1..127] - Overrides the business name.
                'shipping_preference' => '',   // string [1..24] - Shipping preference.
                'locale' => '',                // string [2..10] - The locale for PayPal pages.
                'return_url' => '',            // string - URL where the customer will be redirected after approval.
                'cancel_url' => ''             // string - URL where the customer will be redirected if they cancel.
            ]
        ],
        'ideal' => [
            'name' => '',                      // required - string [3..300] - Name of the account holder.
            'country_code' => '',              // required - string [2] - The two-character country code.
            'bic' => '',                       // string [8..11] - The bank identification code (BIC).
            'experience_context' => [
                'brand_name' => '',            // string [1..127] - Overrides the business name.
                'shipping_preference' => '',   // string [1..24] - Shipping preference.
                'locale' => '',                // string [2..10] - The locale for PayPal pages.
                'return_url' => '',            // string - URL where the customer will be redirected after approval.
                'cancel_url' => ''             // string - URL where the customer will be redirected if they cancel.
            ]
        ],
        'sofort' => [
            'name' => '',                      // required - string [3..300] - Name of the account holder.
            'country_code' => '',              // required - string [2] - The two-character country code.
            'experience_context' => [
                'brand_name' => '',            // string [1..127] - Overrides the business name.
                'shipping_preference' => '',   // string [1..24] - Shipping preference.
                'locale' => '',                // string [2..10] - The locale for PayPal pages.
                'return_url' => '',            // string - URL where the customer will be redirected after approval.
                'cancel_url' => ''             // string - URL where the customer will be redirected if they cancel.
            ]
        ],
        'trustly' => [
            'name' => '',                      // required - string [3..300] - Name of the account holder.
            'country_code' => '',              // required - string [2] - The two-character country code.
            'experience_context' => [
                'brand_name' => '',            // string [1..127] - Overrides the business name.
                'shipping_preference' => '',   // string [1..24] - Shipping preference.
                'locale' => '',                // string [2..10] - The locale for PayPal pages.
                'return_url' => '',            // string - URL where the customer will be redirected after approval.
                'cancel_url' => ''             // string - URL where the customer will be redirected if they cancel.
            ]
        ],
        'apple_pay' => [
            'id' => '',                        // string [1..250] - ApplePay transaction identifier.
            'stored_credential' => [],         // Additional details for storing card information.
            'payment_initiator' => '',         // required - string [1..255] - Initiator of the payment.
            'payment_type' => '',              // required - string [1..255] - Type of payment (ONE_TIME, RECURRING, etc.).
            'usage' => '',                     // string [1..255] - Usage type of the stored credential.
            'network_token' => [],             // Object for tokenized payment card.
            'attributes' => [],                // Additional attributes for Apple Pay.
            'transaction_amount' => [
                'currency_code' => '',         // string [3] - Currency code for the transaction.
                'value' => ''                  // string [1..32] - The value of the transaction.
            ]
        ],
        'google_pay' => [
            'name' => '',                      // string [3..300] - Name of the account holder.
            'email_address' => '',             // string [3..254] - Email address of the account holder.
            'phone_number' => [                // Phone number details.
                'country_code' => '',          // string [1..3] - Country code of the phone number.
                'national_number' => ''        // required - string [1..14] - National phone number.
            ],
            'card' => [                        // Payment card information for Google Pay.
                'name' => '',                  // string [1..300] - Cardholder's name.
                'type' => '',                  // string [1..255] - Payment card type (CREDIT, DEBIT, etc.).
                'billing_address' => [         // Billing address for Google Pay card.
                    'address_line_1' => '',    // string [<=300] - First line of the billing address.
                    'address_line_2' => '',    // string [<=300] - Second line of the billing address.
                    'admin_area_1' => '',      // string [<=300] - State or province.
                    'admin_area_2' => '',      // string [<=120] - City, town, or village.
                    'postal_code' => '',       // string [<=60] - Postal or ZIP code.
                    'country_code' => ''       // string = 2 - ISO 3166-1 country code.
                ]
            ]
        ]
    ];

    // Prepare the final request data
    $paypal_request_data = array(
        'intent' => 'CAPTURE',               // Options: AUTHORIZE or CAPTURE
        'purchase_units' => $purchase_units, // Pass the array of purchase units here
        'payment_source' => $payment_source  // Pass the payment source object here
    );

    // Create the order
    $paypal_result = $paypal->create_order($paypal_request_data);

    // Output the PayPal result
    echo '<pre />';
    print_r($paypal_result);

} catch (Exception $e) {
    // Output any errors that occur
    echo "Error: " . $e->getMessage();
}