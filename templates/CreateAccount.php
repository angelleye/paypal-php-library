<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../autoload.php');


// Create PayPal object.
$PayPalConfig = array(
					  'Sandbox' => $sandbox,
					  'DeveloperAccountEmail' => $developer_account_email,
					  'ApplicationID' => $application_id,
					  'DeviceID' => $device_id,
					  'IPAddress' => $_SERVER['REMOTE_ADDR'],
					  'APIUsername' => $api_username,
					  'APIPassword' => $api_password,
					  'APISignature' => $api_signature,
					  'APISubject' => $api_subject, 
					  'PrintHeaders' => $print_headers,
					  'LogResults' => $log_results, 
					  'LogPath' => $log_path,
					);

$PayPal = new angelleye\PayPal\Adaptive($PayPalConfig);

// Prepare request arrays
$CreateAccountFields = array(
							 'AccountType' => '',  										// Required.  The type of account to be created.  Personal or Premier
							 'CitizenshipCountryCode' => '',  							// Required.  The code of the country to be associated with the business account.  This field does not apply to personal or premier accounts.
							 'ContactPhoneNumber' => '', 								// Required.  The phone number associated with the new account.
							 'HomePhoneNumber' => '', 									// Home phone number associated with the account.
							 'MobilePhoneNumber' => '', 								// Mobile phone number associated with the account.
							 'ReturnURL' => '', 										// Required.  URL to redirect the user to after leaving PayPal pages.
							 'ShowAddCreditCard' => '', 								// Whether or not to show the Add Credit Card option.  Values:  true/false
							 'ShowMobileConfirm' => '', 								// Whether or not to show the mobile confirmation option.  Values:  true/false 
							 'ReturnURLDescription' => '', 								// A description of the Return URL.
							 'UseMiniBrowser' => '', 									// Whether or not to use the minibrowser flow.  Values:  true/false  Note:  If you specify true here, do not specify values for ReturnURL or ReturnURLDescription
							 'CurrencyCode' => '', 										// Required.  Currency code associated with the new account.  
							 'DateOfBirth' => '', 										// Date of birth of the account holder.  YYYY-MM-DDZ format.  For example, 1970-01-01Z
							 'EmailAddress' => '', 										// Required.  Email address.
							 'Saluation' => '', 										// A saluation for the account holder.
							 'FirstName' => '', 										// Required.  First name of the account holder.
							 'MiddleName' => '', 										// Middle name of the account holder.
							 'LastName' => '', 											// Required.  Last name of the account holder.
							 'Suffix' => '',  											// Suffix name for the account holder.
							 'NotificationURL' => '', 									// URL for IPN
							 'PreferredLanguageCode' => '', 							// Required.  The code indicating the language to be associated with the new account.
							 'RegistrationType' => '', 									// Required.  Whether the PayPal user will use a mobile device or the web to complete registration.  This determins whether a key or a URL is returned for the redirect URL.  Allowable values are:  Web
							 'SuppressWelcomeEmail' => '', 								// Whether or not to suppress the PayPal welcome email.  Values:  true/false
							 'PerformExtraVettingOnThisAccount' => '', 					// Whether to subject the account to extra vetting by PayPal before the account can be used.  Values:  true/false
							 'TaxID' => ''												// Tax ID equivalent to US SSN number.   Note:  Currently only supported in Brazil, which uses tax ID numbers such as CPF and CNPJ.
							);
							
$BusinessInfo = array(
						'AverageMonthlyVolume' => '', 									// Required.  The avg. monthly transaction volume of the business for which the PayPal account is being created.  Required for all countries except Japan and Australia, and should not be specified for these countries.
						'AveragePrice' => '', 											// Required.  The avg. price per transaction.  Required for all countries except Japan and Australia, and should not be specified for these countries.
						'BusinessName' => '', 											// Required.  The name of the business for which the PayPal account is being created. 
						'BusinessSubType' => '', 										// The sub type of the business.  Values are:  ENTITY, EMANATION, ESTD_COMMONWEALTH, ESTD_UNDER_STATE_TERRITORY, ESTD_UNDER_FOREIGH_COUNTY, INCORPORATED, NON_INCORPORATED
						'BusinessType' => '', 											// Required.  The type of business.  Values:  CORPORATION, GOVERNMENT, INDIVIDUAL, NONPROFIT, PARTNERSHIP, PROPRIETORSHIP
						'Category' => '', 												// The catgory describing the business.  (ie. 1004 for Baby).  Required unless you specify MerchantCategoryCode.  PayPal uses the industry standard Merchant Category Codes.  Refer to the business' Association Merchant Category Code documentation for this list of codes.
						'CommercialRegistrationLocation' => '', 						// Official commercial registration location for the business.  Required for Germany.  Do not specify this field for other countries.
						'CompanyID' => '', 												// The ID number, equivalent to the tax ID in the US, of the business.  Required for Canada, and some accounts in Australia and Germany.
						'CustomerServiceEmail' => '', 									// Required.  The email address for the customer service dept. of the business.
						'CustomerServicePhone' => '', 									// The phone number for the customer service dept of the business.  Required for US accounts.  Otherwise optional.
						'DateOfEstablishment' => '', 									// The date of establishment for the business.  Required for most countries.
						'DisputeEmail' => '', 											// The email address to contact to dispute charges.
						'DoingBusinessAs' => '', 										// The business name being used if it is not the actual name of the business.
						'EstablishmentCountryCode' => '', 								// The country code of the country where the business was established.
						'EstablishmentState' => '', 									// The state in which the business was established.
						'IncorporationID' => '', 										// The incorporation ID number for the business.
						'MerchantCategoryCode' => '', 									// The category code for the business state in which the business was established.  Required unless you specify both Category and SubCategory.  PayPal uses the industry standard Merchant Category Codes.  Refer to the business' Association Merchant Category Code documentation for this list of codes.
						'PercentageRevenueFromOnline' => '', 							// The percentage of online sales for the business from 0 - 100.  Required for US, Canada, UK, France, Czech Republic, New Zealand, Switzerland, and Israel.  Do not specify for other countries.
						'SalesVenu' => '', 												// The venu type for sales.  Required for all countries except Czech Republic and Australia.  Values are:  WEB, EBAY, OTHER_MARKETPLACE, OTHER
						'SalesVenuDesc' => '', 											// A description of the sales venue.  Required if SalesVenu is OTHER for all countries except Czech Rep and Australia.  Do not specify for CR or Aus
						'SubCategory' => '', 											// The subcategory describing the business. PayPal uses the industry standard Merchant Category Codes.  Refer to the business' Association Merchant Category Code documentation for this list of codes. 
						'VatCountryCode' => '', 										// The country for the VAT.  Optional for business accounts in UK, France, Germany, Spain, Italy, Nertherlands, Sqitzerland, Sweden, and Denmark.  Do not specify for other countries.
						'VatID' => '', 													// The VAT ID number of the business.   Optional for business accounts in UK, France, Germany, Spain, Italy, Nertherlands, Sqitzerland, Sweden, and Denmark. Do not specify for other countries.
						'WebSite' => '', 												// The URL of the website for the business.  Required if SalesVenue is WEB.
						'WorkPhone' => '' 												// Required.  The phone number for the business.  Not required for Mexico.
					);
					
$BusinessAddress = array(
					   'Line1' => '', 													// Required.  Street address.
					   'Line2' => '', 													// Street address 2.
					   'City' => '', 													// Required.  City
					   'State' => '', 													// State or Province
					   'PostalCode' => '', 												// Postal code
					   'CountryCode' => ''												// Required.  The country code.
					   );
					   
$PrinciplePlaceOfBusinessAddress = array(
					   'Line1' => '', 													// Required.  Street address.
					   'Line2' => '', 													// Street address 2.
					   'City' => '', 													// Required.  City
					   'State' => '', 													// State or Province
					   'PostalCode' => '', 												// Postal code
					   'CountryCode' => ''												// Required.  The country code.
					   );

$RegisteredOfficeAddress = array(
					   'Line1' => '', 													// Required.  Street address.
					   'Line2' => '', 													// Street address 2.
					   'City' => '', 													// Required.  City
					   'State' => '', 													// State or Province
					   'PostalCode' => '', 												// Postal code
					   'CountryCode' => ''												// Required.  The country code.
					   );

$BusinessStakeHolder = array(
							'DateOfBirth' => '', 										// The date of birth of the stakeholder in the business.  Format:  YYYY-MM-DDZ  (ie. 1970-01-01Z)
							'FullLegalName' => '', 										// The legal name of the stakeholder in the business for which the account is being created. 
							'Saluation' => '', 											// A saluation for the account holder.
							'FirstName' => '', 											// Required.  First name of the account holder.
							'MiddleName' => '', 										// Middle name of the account holder.
							'LastName' => '', 											// Required.  Last name of the account holder.
							'Suffix' => '',  											// Suffix name for the account holder.
							'Role' => '', 												// The role of the stakeholder in the business.  Values are:  CHAIRMAN, SECRETARY, TREASURER, BENEFICIAL_OWNER, PRIMARY_CONTACT, INDIVIDUAL_PARTNER, NON_INDIVIDUAL_PARTNER, PRIMARY_INDIVIDUAL_PARTNER, DIRECTOR, NO_BENEFICIAL_OWNER
							'CountryCode' => ''											// The country code of the stakeholder's address.
							);

$BusinessStakeHolderAddress = array(
					   'Line1' => '', 													// Required.  Street address.
					   'Line2' => '', 													// Street address 2.
					   'City' => '', 													// Required.  City
					   'State' => '', 													// State or Province
					   'PostalCode' => '', 												// Postal code
					   'CountryCode' => ''												// Required.  The country code.
					   );						


$Address = array(
			   'Line1' => '', 															// Required.  Street address.
			   'Line2' => '', 															// Street address 2.
			   'City' => '', 															// Required.  City
			   'State' => '', 															// State or Province
			   'PostalCode' => '', 														// Postal code
			   'CountryCode' => ''														// Required.  The country code.
			   );

$PartnerFields = array(
					   'Field1' => '', 											// Custom field for use however needed
					   'Field2' => '', 											
					   'Field3' => '', 
					   'Field4' => '', 
					   'Field5' => ''
					   );

$PayPalRequestData = array(
						   'CreateAccountFields' => $CreateAccountFields, 
						   'BusinessInfo' => $BusinessInfo, 
						   'BusinessAddress' => $BusinessAddress, 
						   'PrinciplePlaceOfBusinessAddress' => $PrinciplePlaceOfBusinessAddress, 
						   'RegisteredOfficeAddress' => $RegisteredOfficeAddress, 
						   'BusinessStakeHolder' => $BusinessStakeHolder, 
						   'BusinessStakeHolderAddress' => $BusinessStakeHolderAddress, 
						   'Address' => $Address, 
						   'PartnerFields' => $PartnerFields
						   );

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->CreateAccount($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>