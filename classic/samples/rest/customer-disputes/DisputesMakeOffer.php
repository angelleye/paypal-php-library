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

$PayPal = new \angelleye\PayPal\rest\customerdisputes\CustomerDisputesAPI($configArray);

$dispute_id  = 'PP-D-5617';   // The ID of the dispute for which to make an offer.

$offer_amount = array(
    'currency_code' => 'USD',
    'value' => '23.00'
);
$address_details = array(
    'street_number' => '',     // The street number. Maximum length: 300.
    'street_name' => '',       // The street name. Just Drury in Drury Lane. Maximum length: 300.
    'street_type' => '',       // The street type. For example, avenue, boulevard, road, or expressway. Maximum length: 300.
    'delivery_service' => '',  // The delivery service. Post office box, bag number, or post office name. Maximum length: 300.
    'building_name' => '',     // A named locations that represents the premise. Usually a building name or number or collection of buildings with a common name or number. For example, Craven House.
    'sub_building' => ''       // The first-order entity below a named building or location that represents the sub-premise. Usually a single building within a collection of buildings with a common name. Can be a flat, story, floor, room, or apartment.
);
$return_shipping_address = array(
    'address_line_1' => '',   // The first line of the address. For example, number or street. For example, 173 Drury Lane. Required for data entry and compliance and risk checks. Must contain the full address. Maximum length: 300.
    'address_line_2' => '',   // The second line of the address. For example, suite or apartment number. Maximum length: 300.
    'address_line_3' => '',   // The third line of the address, if needed. For example, a street complement for Brazil, direction text, such as next to Walmart, or a landmark in an Indian address. Maximum length: 300.
    'admin_area_4'   => '',   // The neighborhood, ward, or district. Smaller than admin_area_level_3 or sub_locality. Value is: 1) The postal sorting code that is used in Guernsey and many French territories, such as French Guiana. 2) The fine-grained administrative levels in China. Maximum length: 300.
    'admin_area_3'   => '',   // A sub-locality, suburb, neighborhood, or district. Smaller than admin_area_level_2. Value is: 1) Brazil. Suburb, bairro, or neighborhood. 2)  India. Sub-locality or district. Street name information is not always available but a sub-locality or district can be a very small area. Maximum length: 300.
    'admin_area_2'   => '',   // A city, town, or village. Smaller than admin_area_level_1. Maximum length: 300.
    'admin_area_1'   => '',   // The highest level sub-division in a country, which is usually a province, state, or ISO-3166-2 subdivision. Format for postal delivery. For example, CA and not California. Value, by country, is: 1) UK. A county. 2) US. A state. 3) Canada. A province. 4) Japan. A prefecture. 5) Switzerland. A kanton. Maximum length: 300.
    'postal_code'    => '',   // The postal code, which is the zip code or equivalent. Typically required for countries with a postal code or an equivalent. Maximum length: 60.
    'country_code'   => '',   // Minimum length: 2. Maximum length: 2. Pattern: ^([A-Z]{2}|C2)$ The two-character ISO 3166-1 code that identifies the country or region.
    'address_details'=> array_filter($address_details)
);

$parameters = array(
    'note' => 'Offer refund with replacement item.',                    // The merchant's notes about the claim. PayPal can, but the customer cannot, view these notes. Minimum length: 1. Maximum length: 2000.   
    'offer_amount' => array_filter($offer_amount),                      // The amount proposed to resolve the dispute.
    'offer_type' => 'REFUND_WITH_REPLACEMENT',                          // The type of offer that the merchant proposes for the dispute. The allowed values are: REFUND | REFUND_WITH_RETURN | REFUND_WITH_REPLACEMENT
    'invoice_id' => '',                                                 // The merchant-provided ID of the invoice for the refund. This optional value maps the refund to an invoice ID in the merchant's system.
    'return_shipping_address' => array_filter($return_shipping_address) // The return address for the item. Required when the customer must return an item to the merchant for the MERCHANDISE_OR_SERVICE_NOT_AS_DESCRIBED dispute reason, especially if the refund amount is less than the dispute amount.
);

$response = $PayPal->DisputesMakeOffer($dispute_id,$parameters);

echo "<pre>";
print_r($response);
exit;