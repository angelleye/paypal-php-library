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
     * Prepare Purchase Units
     */
    $purchase_units = array();

    $purchase_unit = array(
        'reference_id' => '',            // string [1..256] - External ID for the purchase unit. Required for multiple purchase units.
        'description' => '',             // string [1..127] - The purchase description.
        'custom_id' => '',               // string [1..255] - External ID for reconciliation. Appears in reports, not visible to payer.
        'invoice_id' => '',              // string [1..127] - External invoice number. Appears in payer's transaction history and emails.
        'soft_descriptor' => '',         // string [1..22] - Dynamic text for statement descriptor (payer's card statement).

        // Amount object
        'amount' => array(               // required - The total order amount with optional breakdown.
            'currency_code' => 'USD',    // The currency code for the payment.
            'value' => '100.00',         // The total amount value (must be positive).
            'breakdown' => array(        // Breakdown of the amount.
                'item_total' => array(   // Total item amount.
                    'currency_code' => 'USD',
                    'value' => '80.00'
                ),
                'shipping' => array(     // Shipping fee.
                    'currency_code' => 'USD',
                    'value' => '10.00'
                ),
                'handling' => array(     // Handling fee.
                    'currency_code' => 'USD',
                    'value' => '5.00'
                ),
                'tax_total' => array(    // Total tax amount.
                    'currency_code' => 'USD',
                    'value' => '5.00'
                ),
                'insurance' => array(    // Insurance fee.
                    'currency_code' => 'USD',
                    'value' => '2.00'
                ),
                'shipping_discount' => array(  // Shipping discount.
                    'currency_code' => 'USD',
                    'value' => '-2.00'
                ),
                'discount' => array(     // General discount.
                    'currency_code' => 'USD',
                    'value' => '-5.00'
                )
            )
        ),

        // Items array - array of purchased items
        'items' => array(
            array(
                'name' => '',                 // required - string [1..127] - The item name or title.
                'quantity' => '1',            // required - string [<=10] - The item quantity (must be a whole number).
                'description' => '',          // string [<=127] - Detailed item description.
                'sku' => '',                  // string [<=127] - The stock keeping unit (SKU) for the item.
                'url' => '',                  // string [1..2048] - The URL to the item.
                'category' => 'PHYSICAL_GOODS',// Enum: DIGITAL_GOODS, PHYSICAL_GOODS, or DONATION.
                'image_url' => '',            // string [1..2048] - URL to the item's image (jpg, gif, png).
                'unit_amount' => array(       // required - The item price or rate per unit.
                    'currency_code' => 'USD',
                    'value' => '40.00'
                ),
                'tax' => array(               // Item tax for each unit.
                    'currency_code' => 'USD',
                    'value' => '2.00'
                ),
                'upc' => array(               // Object - Universal Product Code (UPC) details.
                    'type' => 'UPC-A',        // required - UPC type.
                    'code' => '123456789012'  // required - UPC code.
                )
            ),
            // Add more items if needed
        ),

        // Payee object
        'payee' => array(                // Object - Merchant receiving the payment.
            'email_address' => ''        // Optional - Payee email address.
        ),

        // Payment instructions
        'payment_instruction' => array(  // Object - Additional payment instructions for processing.
            'platform_fees' => array(    // Array of platform fees for the transaction.
                array(
                    'amount' => array(   // required - The fee for the transaction.
                        'currency_code' => 'USD',
                        'value' => '10.00'
                    ),
                    'payee' => array(
                        'email_address' => ''  // Payee email address for the fee.
                    ),
                    'disbursement_mode' => 'INSTANT'  // Enum: INSTANT, DELAYED.
                )
            )
        ),

        // Shipping information
        'shipping' => array(
            'name' => array(
                'full_name' => ''        // Full name of the shipping recipient.
            ),
            'address' => array(
                'address_line_1' => '',  // Primary address line.
                'address_line_2' => '',  // Secondary address line (optional).
                'admin_area_1' => '',    // State or province.
                'admin_area_2' => '',    // City, town, or village.
                'postal_code' => '',     // Postal code.
                'country_code' => ''     // ISO 2-letter country code.
            )
        ),

        // Shipping options
        'shipping_options' => array(
            array(
                'id' => '',              // required - Unique ID for the shipping option.
                'label' => '',           // required - Description of the shipping option.
                'selected' => true,      // required - Boolean - whether this option is pre-selected.
                'amount' => array(
                    'currency_code' => 'USD',
                    'value' => '10.00'   // Shipping cost for the selected option.
                ),
                'type' => 'SHIPPING'     // Enum: SHIPPING, PICKUP_IN_STORE, PICKUP_FROM_PERSON.
            )
        ),

        // Supplementary data
        'supplementary_data' => array(   // Object - Contains supplementary data (if any).
            'card' => array(
                'level_2' => array(
                    'invoice_id' => '',  // Optional - Purchase identification value (up to 127 ASCII characters).
                    'tax_total' => array(// Breakdown of the tax included in the total.
                        'currency_code' => 'USD',
                        'value' => '5.00'
                    )
                ),
                'level_3' => array(
                    'ships_from_postal_code' => '',  // Postal code of the shipping location.
                    'line_items' => array(          // Array of line items for level 3 data.
                        array(
                            'name' => '',           // Item name.
                            'quantity' => '1',      // Item quantity.
                            'unit_amount' => array(
                                'currency_code' => 'USD',
                                'value' => '40.00'
                            )
                        )
                    ),
                    'shipping_amount' => array(
                        'currency_code' => 'USD',
                        'value' => '10.00'
                    )
                )
            )
        )
    );

    // Push the purchase unit into the array
    array_push($purchase_units, $purchase_unit);

    // Prepare the final request data
    $paypal_request_data = array(
        'intent' => 'CAPTURE',  // Options: AUTHORIZE or CAPTURE
        'purchase_units' => $purchase_units  // Pass the array of purchase units here
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