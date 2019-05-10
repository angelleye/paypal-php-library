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

$template_id = 'TEMP-831584274S0021100';                  // Required. The ID of the template to update.

$InvoiceItemArray = array();

$InvoiceItem = array(
        'Name'          => '',                            // Name of the item. 200 characters max.
        'Description'   => '',                            // Description of the item. 1000 characters max.
        'Quantity'      => '',                            // Quantity of the item. Range of -10000 to 10000.
        'Date'          => '',                            // The date when the item or service was provided. The date format is *yyyy*-*MM*-*dd* *z* as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6). 
        'Discount'      => array(
                                'Percent' => ''           // Cost in percent. Range of 0 to 100.
                            ),
        'UnitOfMeasure' => '',                            // Valid Values: ["QUANTITY", "HOURS", "AMOUNT"]. The unit of measure of the item being invoiced.        
        'UnitPrice'     => array(
                                'Currency' => '',         // 3 letter currency code as defined by ISO 4217.
                                'Value'    => ''          // amount up to N digit after the decimals separator as defined in ISO 4217 for the appropriate currency code.
                            ),                            // Unit price of the item. Range of -1,000,000 to 1,000,000.
        'Tax'           => array(
                                'Name'    => '',
                                'Percent' => '',    
                            )                           
);

array_push($InvoiceItemArray, $InvoiceItem);

$merchantInfo = array(
    'Email' => 'paypal-facilitator@angelleye.com',         // The merchant email address. Maximum length is 260 characters.
    'FirstName' => 'Sandbox',                              // The merchant first name. Maximum length is 30 characters.
    'LastName'  => 'Testerson',                            // The merchant last name. Maximum length is 30 characters.
    'BusinessName' => 'sb\'s Test Store',                  // The merchant company business name. Maximum length is 100 characters.
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
    'Email' => 'paypal-buyer@angelleye.com',              // The invoice recipient email address. Maximum length is 260 characters.
    'FirstName' => '',                                          // The invoice recipient first name. Maximum length is 30 characters.
    'LastName'  => '',                                          // The invoice recipient last name. Maximum length is 30 characters.
    'BusinessName' => 'AE',                              // The invoice recipient company business name. Maximum length is 100 characters.
    'Language' => '',                                           // The language in which the email was sent to the payer. Used only when the payer does not have a PayPal account. Valid Values: ["da_DK", "de_DE", "en_AU", "en_GB", "en_US", "es_ES", "es_XC", "fr_CA", "fr_FR", "fr_XC", "he_IL", "id_ID", "it_IT", "ja_JP", "nl_NL", "no_NO", "pl_PL", "pt_BR", "pt_PT", "ru_RU", "sv_SE", "th_TH", "tr_TR", "zh_CN", "zh_HK", "zh_TW", "zh_XC"]
    'AdditionalInfo' => 'Business hours 10:00 AM to 7:30 PM.',  // Additional information, such as business hours. Maximum length is 40 characters.
    'NotificationChannel' => '',                                // Valid Values: ["SMS", "EMAIL"]. Preferred notification channel of the payer. Email by default.
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

$templateDataCcInfo =  '';                                      // For invoices sent by email, one or more email addresses to which to send a Cc: copy of the notification. Supports only email addresses under participant.

$templateData = array(    
    'Reference'  => '',                                  // Reference data, such as PO number, to add to the invoice. Maximum length is 60 characters.    
    'AllowPartialPayment' => '',                         // Indicates whether the invoice allows a partial payment. If set to `false`, invoice must be paid in full. If set to `true`, the invoice allows partial payments. Default is `false`.   
    'MinimumAmountDue' => array(
                                'Currency' => '',                       // 3 letter currency code as defined by ISO 4217.
                                'Value'    => ''                        // amount up to N digit after the decimals separator as defined in ISO 4217 for the appropriate currency code.
                            ),    
    'TaxCalculatedAfterDiscount'    => '',              // Indicates whether tax is calculated before or after a discount. If set to `false`, the tax is calculated before a discount. If set to `true`, the tax is calculated after a discount. Default is `false`.
    'TaxInclusive' => '',                               // Default is `false`. Indicates whether the unit price includes tax. 
    'Note'  => '',                                      // Note to the payer. 4000 characters max.
    'MerchantMemo'  => '',                              // A private bookkeeping memo for the merchant. Maximum length is 150 characters.
    'LogoUrl'   => '',                                  // Full URL of an external image to use as the logo. Maximum length is 4000 characters.
    'TotalAmount' => array(
                                'Currency' => '',                       // 3 letter currency code as defined by ISO 4217.
                                'Value'    => ''                        // amount up to N digit after the decimals separator as defined in ISO 4217 for the appropriate currency code.
                     ),                                    
);
$templateDiscount = array(
    'Percent' => '',                                    // The invoice level discount, as a percent or an amount value.
);



$paymentTerm = array(
    'TermType' => '',                                         // Valid Values: ["DUE_ON_RECEIPT", "DUE_ON_DATE_SPECIFIED", "NET_10", "NET_15", "NET_30", "NET_45", "NET_60", "NET_90", "NO_DUE_DATE"]. The terms by which the invoice payment is due.
    'DueDate'  => ''                                          // The date when the invoice payment is due. This date must be a future date. Date format is *yyyy*-*MM*-*dd* *z*, as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).   
);
$attachments = array(
    'Name' => 'AttachmentImage',                                 // Name of the file attached.
    'Url'  => 'https://cdn.pixabay.com/photo/2016/09/16/19/15/gear-1674891_960_720.png' // URL of the attached file that can be downloaded.                                             // URL of the attached file that can be downloaded.
);


$TemplateSettingsMetadata = array(
    'Hidden' => 'true'                                           // Indicates whether this field should be hidden. default is false
);

$TemplateSettings = array(
    'FieldName'         => 'items.date' ,                        // The field name (for any field in template_data) for which the corresponding display preferences will be mapped to.
);
/*Required */
$Template = array(
    'TemplateId'    => '',                                       // Unique identifier id of the template.
    'Name'          => "AngellEye Template" . rand(),            // Name of the template.
    'Default'       => 'true',                                   // Indicates that this template is merchant's default. There can be only one template which can be a default.
    'UnitOfMeasure' => 'HOURS',                                  // Unit of measure for the template, possible values are Quantity, Hours, Amount.
    'Custom'        => '',                                       // Indicates whether this is a custom template created by the merchant. Non custom templates are system generated
);

$requestData = array(
    'InvoiceItemArray' => $InvoiceItemArray,
    'merchantInfo'     => $merchantInfo,
    'merchantPhone'    => $merchantPhone,
    'merchantAddress'  => $merchantAddress,
    'billingInfo'      => $billingInfo,
    'billingInfoPhone' => $billingInfoPhone,
    'billingInfoAddress' => $billingInfoAddress,
    'shippingInfo'     => $shippingInfo,
    'shippingInfoPhone' => $shippingInfoPhone,
    'shippingInfoAddress' => $shippingInfoAddress,
    'templateData'      => $templateData,
    'templateDiscount' => $templateDiscount,
    'paymentTerm'      => $paymentTerm,
    'attachments'      => $attachments,
    'TemplateSettingsMetadata' => $TemplateSettingsMetadata,
    'TemplateSettings' => $TemplateSettings,
    'Template'         => $Template,
    'TemplateDataCcInfo' => $templateDataCcInfo
);

$returnArray = $PayPal->UpdateInvoiceTemplate($template_id,$requestData);
echo "<pre>";
print_r($returnArray);
