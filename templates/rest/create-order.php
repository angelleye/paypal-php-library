<?php
require_once('../../includes/config.php');
require_once('../../autoload.php');

$paypal_config = array(
    'sandbox' => $sandbox,
    'rest_client_id' => $rest_client_id,
    'rest_client_secret' => $rest_client_secret,
);

$paypal = new \angelleye\PayPal\Rest($paypal_config);

$order_data = [
    'intent' => '', // [CAPTURE, AUTHORIZE] - Indicates if the payment is for immediate capture or authorization.
    'purchase_units' => [
        [
            'reference_id' => '', // [Optional] Unique ID for the purchase unit.
            'description' => '', // [Optional] Description of the purchase.
            'custom_id' => '', // [Optional] Custom identifier for tracking.
            'invoice_id' => '', // [Optional] Invoice number for tracking.
            'soft_descriptor' => '', // [Optional] Soft descriptor for the transaction.

            'amount' => [
                'currency_code' => '', // [Required] 3-character currency code (e.g., 'USD').
                'value' => '', // [Required] Total value of the transaction.
                'breakdown' => [
                    'item_total' => [
                        'currency_code' => '', // [Required if items exist] Currency code for item total.
                        'value' => '' // [Required if items exist] Sum of all item amounts.
                    ],
                    'shipping' => [
                        'currency_code' => '', // [Optional] Currency code for shipping.
                        'value' => '' // [Optional] Total shipping amount.
                    ],
                    'handling' => [
                        'currency_code' => '', // [Optional] Currency code for handling.
                        'value' => '' // [Optional] Total handling amount.
                    ],
                    'tax_total' => [
                        'currency_code' => '', // [Optional] Currency code for taxes.
                        'value' => '' // [Optional] Total tax amount.
                    ],
                    'shipping_discount' => [
                        'currency_code' => '', // [Optional] Currency code for shipping discount.
                        'value' => '' // [Optional] Total shipping discount amount.
                    ]
                ]
            ],

            'payee' => [
                'email_address' => '' // [Optional] The merchant's PayPal email address.
            ],

            'items' => [
                [
                    'name' => '', // [Required] Name of the item.
                    'description' => '', // [Optional] Description of the item.
                    'quantity' => '', // [Required] Item quantity.
                    'unit_amount' => [
                        'currency_code' => '', // [Required] Currency code for the item price.
                        'value' => '' // [Required] Price per unit.
                    ],
                    'tax' => [
                        'currency_code' => '', // [Optional] Currency code for item tax.
                        'value' => '' // [Optional] Tax amount per unit.
                    ],
                    'sku' => '', // [Optional] Stock keeping unit (SKU) for the item.
                    'category' => '' // [Optional] Category of the item (PHYSICAL_GOODS, DIGITAL_GOODS).
                ]
            ],

            'shipping' => [
                'method' => '', // [Optional] Shipping method (e.g., 'United States Postal Service').
                'address' => [
                    'address_line_1' => '', // [Required] First line of the address.
                    'address_line_2' => '', // [Optional] Second line of the address.
                    'admin_area_2' => '', // [Required] City or town.
                    'admin_area_1' => '', // [Required] State or province.
                    'postal_code' => '', // [Required] Postal code.
                    'country_code' => '' // [Required] Country code (2-character ISO 3166-1).
                ]
            ]
        ]
    ],

    'application_context' => [
        'return_url' => '', // [Required] URL for redirection after approval.
        'cancel_url' => '', // [Required] URL for redirection after cancellation.
        'brand_name' => '', // [Optional] Merchant’s brand name to display in PayPal.
        'locale' => '', // [Optional] Locale for PayPal pages (e.g., 'en-US').
        'landing_page' => '', // [Optional] Page type ('LOGIN', 'BILLING', 'NO_PREFERENCE').
        'user_action' => '', // [Optional] Pay Now button ('CONTINUE', 'PAY_NOW').
        'shipping_preference' => '' // [Optional] Address preference ('NO_SHIPPING', 'GET_FROM_FILE', 'SET_PROVIDED_ADDRESS').
    ],

    'payment_source' => [
        'paypal' => [
            'experience_context' => [
                'brand_name' => '', // [Optional] Override business name in PayPal.
                'locale' => '', // [Optional] Locale for PayPal pages.
                'landing_page' => '', // [Optional] Type of landing page.
                'shipping_preference' => '', // [Optional] Shipping preference.
                'return_url' => '', // [Required] URL after successful approval.
                'cancel_url' => '' // [Required] URL after cancellation.
            ]
        ]
    ],

    'payer' => [
        'email_address' => '', // [Optional] Payer’s email address.
        'name' => [
            'given_name' => '', // [Optional] Payer’s first name.
            'surname' => '' // [Optional] Payer’s last name.
        ],
        'phone' => [
            'phone_number' => [
                'national_number' => '' // [Optional] Payer’s phone number.
            ]
        ],
        'address' => [
            'address_line_1' => '', // [Optional] First line of payer’s address.
            'address_line_2' => '', // [Optional] Second line of payer’s address.
            'admin_area_2' => '', // [Optional] Payer’s city or town.
            'admin_area_1' => '', // [Optional] Payer’s state or province.
            'postal_code' => '', // [Optional] Payer’s postal code.
            'country_code' => '' // [Optional] Payer’s country code (2-character ISO 3166-1).
        ]
    ]
];

try {
    $paypal_result = $paypal->create_order($order_data);

    // Output the PayPal result
    echo '<pre />';
    print_r($paypal_result);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}