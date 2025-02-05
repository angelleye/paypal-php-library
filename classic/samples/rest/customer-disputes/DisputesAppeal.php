<?php

/**
 *  Note : To appeal a dispute, use the appeal link in the HATEOAS links from the show dispute details response. 
 *  If this link does not appear, you cannot appeal the dispute. 
 *  Submit new evidence as a document or notes in the JSON request body.
 */

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

$dispute_id  = 'PP-D-5617';   // The ID of the dispute for which to accept a claim.


//  carrier_name enum The name of the carrier for the shipment of the transaction for this dispute. The possible values are:
// | UPS | USPS | FEDEX | AIRBORNE_EXPRESS | DHL | AIRSURE | ROYAL_MAIL | PARCELFORCE | SWIFTAIR | OTHER | UK_PARCELFORCE 
// | UK_ROYALMAIL_SPECIAL UK_ROYALMAIL_RECORDED | UK_ROYALMAIL_INT_SIGNED | UK_ROYALMAIL_AIRSURE | UK_UPS | UK_FEDEX 
// | UK_AIRBORNE_EXPRESS | UK_DHL UK_OTHER | UK_CANNOT_PROV_TRACK | CA_CANADA_POST | CA_PUROLATOR | CA_CANPAR | CA_LOOMIS  
// | CA_TNT | CA_OTHER | CA_CANNOT_PROV_TRACK DE_DP_DHL_WITHIN_EUROPE | DE_DP_DHL_T_AND_T_EXPRESS | DE_DHL_DP_INTL_SHIPMENTS | DE_GLS | DE_DPD_DELISTACK | DE_HERMES
// | DE_UPS | DE_FEDEX | DE_TNT | DE_OTHER | FR_CHRONOPOST | FR_COLIPOSTE | FR_DHL | FR_UPS | FR_FEDEX | FR_TNT | FR_GLS | FR_OTHER
// | IT_POSTE_ITALIA | IT_DHL | IT_UPS | IT_FEDEX | IT_TNT | IT_GLS | IT_OTHER | AU_AUSTRALIA_POST_EP_PLAT | AU_AUSTRALIA_POST_EPARCEL | | | AU_AUSTRALIA_POST_EMS AU_DHL 
// | AU_STAR_TRACK_EXPRESS | AU_UPS | AU_FEDEX | AU_TNT | AU_TOLL_IPEC | AU_OTHER | FR_SUIVI | IT_EBOOST_SDA
// | ES_CORREOS_DE_ESPANA | ES_DHL | ES_UPS | ES_FEDEX | ES_TNT | ES_OTHER | AT_AUSTRIAN_POST_EMS | AT_AUSTRIAN_POST_PPRIME | BE_CHRONOPOST | 
// | BE_TAXIPOST CH_SWISS_POST_EXPRES | CH_SWISS_POST_PRIORITY | CN_CHINA_POST | HK_HONGKONG_POST | IE_AN_POST_SDS_EMS
// | IE_AN_POST_SDS_PRIORITY | IE_AN_POST_REGISTERED | IE_AN_POST_SWIFTPOST | IN_INDIAPOST | JP_JAPANPOST | KR_KOREA_POST | NL_TPG | | SG_SINGPOST
// | TW_CHUNGHWA_POST | CN_CHINA_POST_EMS | CN_FEDEX | CN_TNT | CN_UPS | CN_OTHER | NL_TNT | NL_DHL | NL_UPS | NL_FEDEX | NL_KIALA | BE_KIALA 
// | PL_POCZTA_POLSKA PL_POCZTEX | PL_GLS | PL_MASTERLINK | PL_TNT | PL_DHL | PL_UPS | PL_FEDEX | JP_SAGAWA_KYUU_BIN | JP_NITTSU_PELICAN_BIN | JP_KURO_NEKO_YAMATO_UNYUU 
// | JP_TNT | JP_DHL | JP_UPS | JP_FEDEX | NL_PICKUP | NL_INTANGIBLE | NL_ABC_MAIL | HK_FOUR_PX_EXPRESS | HK_FLYT_EXPRESS


$tracking_info = array(
    'carrier_name' => 'FEDEX',          // The name of the carrier for the shipment of the transaction for this dispute.
    'carrier_name_other' => '',         // This field capture the name of carrier in free form text for unavailable carriers from existing list.
    'tracking_url' => '',               // The URL to track the dispute-related transaction shipment.
    'tracking_number' => '122533485'    // The number to track the dispute-related transaction shipment.
);

$refund_ids =array(
    'refund_id' => ''                   // The ID of the refunded transaction.
);

$evidence_info = array(
    'tracking_info' => $tracking_info,  // An array of relevant tracking information for the transaction involved in this dispute.
    'refund_ids' => $refund_ids         // An array of refund IDs for the transaction involved in this dispute.    
);

$documents = array(
    'name' => '',                       // The document name.
    'size' => ''                        // The document size.
);


$evidences = array(
    'evidence_type' => 'PROOF_OF_FULFILLMENT',  // PROOF_OF_FULFILLMENT | PROOF_OF_REFUND | PROOF_OF_DELIVERY_SIGNATURE | PROOF_OF_RECEIPT_COPY | RETURN_POLICY | BILLING_AGREEMENT | PROOF_OF_RESHIPMENT | ITEM_DESCRIPTION | POLICE_REPORT | AFFIDAVIT | PAID_WITH_OTHER_METHOD | COPY_OF_CONTRACT | TERMINAL_ATM_RECEIPT | PRICE_DIFFERENCE_REASON | SOURCE_CONVERSION_RATE | BANK_STATEMENT | CREDIT_DUE_REASON | REQUEST_CREDIT_RECEIPT | PROOF_OF_RETURN | CREATE | CHANGE_REASON | OTHER
    'evidence_info' => $evidence_info,          // The evidence-related information.
    'documents' => $documents,                  // An array of evidence documents.
    'notes' => '',                              // Any evidence-related notes. Maximum length: 2000.
    'item_id' => ''                             // The item ID. If the merchant provides multiple pieces of evidence and the transaction has multiple item IDs, the merchant can use this value to associate a piece of evidence with an item ID.    
);

$parameters = array(
    'evidences' => $evidences,                  // An array of evidences for the dispute.
);

$response = $PayPal->DisputesAppeal($dispute_id,$parameters);

echo "<pre>";
print_r($response);
exit;