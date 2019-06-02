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

$PayPal = new \angelleye\PayPal\rest\paymentexperience\PaymentExperianceAPI($configArray);

$profileID = 'TXP-0Y6000321B436213T';                                        //Required. The ID of the profile to update.

$FlowConfig = array(
    'LandingPageType'     => 'Billing',                                      // The type of landing page to display on the PayPal site for user checkout. Set to `Billing` to use the non-PayPal account landing page. Set to `Login` to use the PayPal account login landing page.
    'BankTxnPendingUrl'   => '',                                             // The merchant site URL to display after a bank transfer payment. Valid for only the Giropay or bank transfer payment method in Germany.
    'UserAction'          => '',                                             // Defines whether buyers can complete purchases on the PayPal or merchant website.
    'ReturnUriHttpMethod' => '',                                             // Defines the HTTP method to use to redirect the user to a return URL. A valid value is `GET` or `POST`.    
);

$presentation = array(
    'BrandName'           => 'AngellEye Paypal',                               // A label that overrides the business name in the PayPal account on the PayPal pages. Character length and limitations: 127 single-byte alphanumeric characters.
    'LogoImage'           => '',                                             // A URL to the logo image. A valid media type is `.gif`, `.jpg`, or `.png`. The maximum width of the image is 190 pixels. The maximum height of the image is 60 pixels. PayPal crops images that are larger. PayPal places your logo image at the top of the cart review area. PayPal recommends that you store the image on a secure (HTTPS) server. Otherwise, web browsers display a message that checkout pages contain non-secure items. Character length and limit: 127 single-byte alphanumeric characters.
    'LocaleCode'          => '',                                             // The locale of pages displayed by PayPal payment experience. A valid value is `AU`, `AT`, `BE`, `BR`, `CA`, `CH`, `CN`, `DE`, `ES`, `GB`, `FR`, `IT`, `NL`, `PL`, `PT`, `RU`, or `US`. A 5-character code is also valid for languages in specific countries: `da_DK`, `he_IL`, `id_ID`, `ja_JP`, `no_NO`, `pt_BR`, `ru_RU`, `sv_SE`, `th_TH`, `zh_CN`, `zh_HK`, or `zh_TW`.
    'ReturnUrlLabel'      => '',                                             // A label to use as hypertext for the return to merchant link.
    'NoteToSellerLabel'   => '',                                             // A label to use as the title for the note to seller field. Used only when `allow_note` is `1`.    
);

$InputFields = array(
    'AllowNote'           => '',                                             // Type bool. Indicates whether the buyer can enter a note to the merchant on the PayPal page during checkout.
    'NoShipping'          => '',                                             // Indicates whether PayPal displays shipping address fields on the experience pages. Valid value is `0`, `1`, or `2`. Set to `0` to display the shipping address on the PayPal pages. Set to `1` to redact shipping address fields from the PayPal pages. Set to `2` to not pass the shipping address but instead get it from the buyer's account profile. For digital goods, this field is required and value must be `1`.
    'AddressOverride'     => '',                                             // Indicates whether to display the shipping address that is passed to this call rather than the one on file with PayPal for this buyer on the PayPal experience pages. Valid value is `0` or `1`. Set to `0` to display the shipping address on file. Set to `1` to display the shipping address supplied to this call; the buyer cannot edit this shipping address.    
);

$WebProfile = array(
    'Name'                => "AngellEye T-Shirt Shop" . uniqid(),                 // Required. | The web experience profile name. Unique for a specified merchant's profiles.
    'Temporary'           => '',                                             // Indicates whether the profile persists for three hours or permanently. Set to `false` to persist the profile permanently. Set to `true` to persist the profile for three hours.    
);

$requestData = array(
    'FlowConfig'   => $FlowConfig,
    'presentation' => $presentation,
    'InputFields'  => $InputFields,
    'WebProfile'   => $WebProfile
);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->UpdateWebProfile($requestData,$profileID);

// Write the contents of the response array to the screen for demo purposes.
echo "<pre>";
var_dump($PayPalResult);

