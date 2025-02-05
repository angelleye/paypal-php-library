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

$PayPal = new \angelleye\PayPal\rest\invoice\InvoiceAPI($configArray);

// Merchant informations is Required for creating new Invoice. 
$merchantInfo = array(
    'Email' => '',                                           // The merchant email address. Maximum length is 260 characters.
    'FirstName' => '',                                       // The merchant first name. Maximum length is 30 characters.
    'LastName'  => '',                                       // The merchant last name. Maximum length is 30 characters.        
    'BusinessName' => '',                                    // The merchant company business name. Maximum length is 100 characters.
);

$merchantPhone = array(
    'CountryCode' => '',                                     // Country code (from in E.164 format).
    'NationalNumber' => '',                                  // In-country phone number (from in E.164 format).
    'Extension' => '',                                       // Phone extension.
);

$merchantAddress = array(
    'Line1' => '',                                            // Line 1 of the Address (eg. number, street, etc).
    'Line2' => '',                                            // Optional line 2 of the Address (eg. suite, apt #, etc.).
    'City'  => '',                                            // City name.
    'CountryCode' => '',                                      // 2 letter country code.    
    'PostalCode'  => '',                                      // Zip code or equivalent is usually required for countries that have them. For list of countries that do not have postal codes please refer to http://en.wikipedia.org/wiki/Postal_code. 
    'State'       => '',                                      // 2 letter code for US states, and the equivalent for other countries.     
);

$billingInfo = array(
    'Email' => '',                                            // The invoice recipient email address. Maximum length is 260 characters.
    'FirstName' => '',                                        // The invoice recipient first name. Maximum length is 30 characters.
    'LastName'  => '',                                        // The invoice recipient last name. Maximum length is 30 characters. 
    'BusinessName' => '',                                     // The invoice recipient company business name. Maximum length is 100 characters.
    'Language' => '',                                         // The language in which the email was sent to the payer. Used only when the payer does not have a PayPal account. Valid Values: ["da_DK", "de_DE", "en_AU", "en_GB", "en_US", "es_ES", "es_XC", "fr_CA", "fr_FR", "fr_XC", "he_IL", "id_ID", "it_IT", "ja_JP", "nl_NL", "no_NO", "pl_PL", "pt_BR", "pt_PT", "ru_RU", "sv_SE", "th_TH", "tr_TR", "zh_CN", "zh_HK", "zh_TW", "zh_XC"]
    'AdditionalInfo' => '',                                   // Additional information, such as business hours. Maximum length is 40 characters.    
    'NotificationChannel' => '',                              // Valid Values: ["SMS", "EMAIL"]. Preferred notification channel of the payer. Email by default.
    
);

$billingInfoPhone = array(
    'CountryCode' => '',                                     // Country code (from in E.164 format).
    'NationalNumber' => '',                                  // In-country phone number (from in E.164 format).
    'Extension' => '',                                       // Phone extension.
);

$billingInfoAddress = array(
    'Line1' => '',                                            // Line 1 of the Address (eg. number, street, etc).
    'Line2' => '',                                            // Optional line 2 of the Address (eg. suite, apt #, etc.).
    'City'  => '',                                            // City name.
    'CountryCode' => '',                                      // 2 letter country code.    
    'PostalCode'  => '',                                      // Zip code or equivalent is usually required for countries that have them. For list of countries that do not have postal codes please refer to http://en.wikipedia.org/wiki/Postal_code. 
    'State'       => '',                                      // 2 letter code for US states, and the equivalent for other countries.         
);
  
$itemArray = array();

$item1 = array(
    'Name' => '',                                           // Name of the item. 200 characters max.
    'Description' => '',                                    // Description of the item. 1000 characters max.
    'Quantity' => '',                                       // Quantity of the item. Range of -10000 to 10000.
    'UnitPrice'  => array(
                        'Currency' => '',                   // 3 letter currency code as defined by ISO 4217.     
                        'Value'    => ''                    // amount up to N digit after the decimals separator as defined in ISO 4217 for the appropriate currency code. 
                       ),                                      // Unit price of the item. Range of -1,000,000 to 1,000,000.
    'Tax' => array(
                        'Name'    => '',                    // The tax name. Maximum length is 20 characters. 
                        'Percent' => '',                    // The rate of the specified tax. Valid range is from 0.001 to 99.999.                        
                     ),                                        // Tax associated with the item.
    'Date' => '',                                           // The date when the item or service was provided. The date format is *yyyy*-*MM*-*dd* *z* as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).
    'Discount' => array(
                        'Percent' => '',                        
                    ),                                         // The item discount, as a percent or an amount value.
    'UnitOfMeasure' => '',                                  // Valid Values: ["QUANTITY", "HOURS", "AMOUNT"] The unit of measure of the item being invoiced.
);

array_push($itemArray,$item1);

$item2 = array(
    'Name' => '',                                           // Name of the item. 200 characters max.
    'Description' => '',                                    // Description of the item. 1000 characters max.
    'Quantity' => '',                                       // Quantity of the item. Range of -10000 to 10000.
    'UnitPrice'  => array(
                        'Currency' => '',                   // 3 letter currency code as defined by ISO 4217.     
                        'Value'    => ''                    // amount up to N digit after the decimals separator as defined in ISO 4217 for the appropriate currency code. 
                       ),                                      // Unit price of the item. Range of -1,000,000 to 1,000,000.
    'Tax' => array(
                        'Name'    => '',                    // The tax name. Maximum length is 20 characters. 
                        'Percent' => '',                    // The rate of the specified tax. Valid range is from 0.001 to 99.999.                        
                     ),                                        // Tax associated with the item.
    'Date' => '',                                           // The date when the item or service was provided. The date format is *yyyy*-*MM*-*dd* *z* as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).
    'Discount' => array(
                        'Percent' => '',                        
                    ),                                         // The item discount, as a percent or an amount value.
    'UnitOfMeasure' => '',                                  // Valid Values: ["QUANTITY", "HOURS", "AMOUNT"] The unit of measure of the item being invoiced.
);

array_push($itemArray,$item2);

$finalDiscountForInvoice = array(
    'Percent' => ''                                         // The rate of the specified Discount. Valid range is from 0.001 to 99.999.                         
);

$shippingInfo = array(
    'FirstName' => '',                                      // The invoice recipient first name. Maximum length is 30 characters. 
    'LastName'  => '',                                      // The invoice recipient last name. Maximum length is 30 characters.
    'BusinessName' => '',                                   // The invoice recipient company business name. Maximum length is 100 characters.     
);

$shippingInfoPhone = array(
    'CountryCode' => '',                                     // Country code (from in E.164 format).
    'NationalNumber' => '',                                  // In-country phone number (from in E.164 format).
    'Extension' => '',                                       // Phone extension.
);

$shippingInfoAddress = array(
    'Line1' => '',                                            // Line 1 of the Address (eg. number, street, etc).
    'Line2' => '',                                            // Optional line 2 of the Address (eg. suite, apt #, etc.).
    'City'  => '',                                            // City name.
    'CountryCode' => '',                                      // 2 letter country code.    
    'PostalCode'  => '',                                      // Zip code or equivalent is usually required for countries that have them. For list of countries that do not have postal codes please refer to http://en.wikipedia.org/wiki/Postal_code. 
    'State'       => '',                                      // 2 letter code for US states, and the equivalent for other countries.     
);

$paymentTerm = array(
    'TermType' => '',                                         // Valid Values: ["DUE_ON_RECEIPT", "DUE_ON_DATE_SPECIFIED", "NET_10", "NET_15", "NET_30", "NET_45", "NET_60", "NET_90", "NO_DUE_DATE"]. The terms by which the invoice payment is due.
    'DueDate'  => ''                                          // The date when the invoice payment is due. This date must be a future date. Date format is *yyyy*-*MM*-*dd* *z*, as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).   
);

$invoiceData = array(
    'Note' => '',                                             // Note to the payer. 4000 characters max.
    'Number' => '',                                           // Unique number that appears on the invoice. If left blank will be auto-incremented from the last number. 25 characters max.
    'TemplateId' => '',                                       // The template ID used for the invoice. Useful for copy functionality.   
    'Uri' => '',                                              // URI of the invoice resource.
    'MerchantMemo' => '',                                     // A private bookkeeping memo for the merchant. Maximum length is 150 characters.
    'LogoUrl'      => '',                                     // Full URL of an external image to use as the logo. Maximum length is 4000 characters.    
);


$requestData =array(
    'merchantInfo'            => $merchantInfo,
    'merchantPhone'           => $merchantPhone,
    'merchantAddress'         => $merchantAddress,
    'billingInfo'             => $billingInfo,
    'billingInfoPhone'        => $billingInfoPhone,
    'billingInfoAddress'      => $billingInfoAddress,
    'itemArray'               => $itemArray,
    'finalDiscountForInvoice' => $finalDiscountForInvoice,
    'shippingInfo'            => $shippingInfo,
    'shippingInfoPhone'       => $shippingInfoPhone,
    'shippingInfoAddress'     => $shippingInfoAddress,
    'paymentTerm'             => $paymentTerm,
    'invoiceData'             => $invoiceData
);

$returnArray = $PayPal->CreateInvoice($requestData);
echo "<pre>";
print_r($returnArray);
