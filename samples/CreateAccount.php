<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../autoload.php');


// Create PayPal object.
$PayPalConfig = array(
					  'Sandbox' => $sandbox,
					  'DeveloperAccountEmail' => $developer_account_email,
					  'ApplicationID' => $application_id,
					  'DeviceID' => '',
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
							 'AccountType' => 'Premier',  										// Required.  The type of account to be created.  Personal or Premier
							 'CitizenshipCountryCode' => 'US',  							// Required.  The code of the country to be associated with the business account.  This field does not apply to personal or premier accounts.
							 'ContactPhoneNumber' => '555-555-5555', 								// Required.  The phone number associated with the new account.
							 'HomePhoneNumber' => '555-555-5555', 									// Home phone number associated with the account.
							 'MobilePhoneNumber' => '555-555-4444', 								// Mobile phone number associated with the account.
							 'ReturnURL' => $domain.'return.php', 										// Required.  URL to redirect the user to after leaving PayPal pages.
							 'ShowAddCreditCard' => 'true', 								// Whether or not to show the Add Credit Card option.  Values:  true/false
							 'ShowMobileConfirm' => '', 								// Whether or not to show the mobile confirmation option.  Values:  true/false 
							 'ReturnURLDescription' => 'Home Page', 								// A description of the Return URL.
							 'UseMiniBrowser' => 'false', 									// Whether or not to use the minibrowser flow.  Values:  true/false  Note:  If you specify true here, do not specify values for ReturnURL or ReturnURLDescription
							 'CurrencyCode' => 'USD', 										// Required.  Currency code associated with the new account.  
							 'DateOfBirth' => '1982-04-09Z', 										// Date of birth of the account holder.  YYYY-MM-DDZ format.  For example, 1970-01-01Z
							 'EmailAddress' => 'sandbox2@angelleye.com', 										// Required.  Email address.
							 'Saluation' => '', 										// A saluation for the account holder.
							 'FirstName' => 'Tester', 										// Required.  First name of the account holder.
							 'MiddleName' => '', 										// Middle name of the account holder.
							 'LastName' => 'Testerson', 											// Required.  Last name of the account holder.
							 'Suffix' => '',  											// Suffix name for the account holder.
							 'NotificationURL' => $domain.'paypal/ipn/ipn-listener.php', 									// URL for IPN
							 'PreferredLanguageCode' => '', 							// Required.  The code indicating the language to be associated with the new account.
							 'RegistrationType' => 'Web', 									// Required.  Whether the PayPal user will use a mobile device or the web to complete registration.  This determins whether a key or a URL is returned for the redirect URL.  Allowable values are:  Web
							 'SuppressWelcomeEmail' => '', 								// Whether or not to suppress the PayPal welcome email.  Values:  true/false
							 'PerformExtraVettingOnThisAccount' => '', 					// Whether to subject the account to extra vetting by PayPal before the account can be used.  Values:  true/false
							 'TaxID' => ''												// Tax ID equivalent to US SSN number.   Note:  Currently only supported in Brazil, which uses tax ID numbers such as CPF and CNPJ.
							);
							
$Address = array(
			   'Line1' => '1503 Main St.', 													// Required.  Street address.
			   'Line2' => '376', 													// Street address 2.
			   'City' => 'Kansas City', 													// Required.  City
			   'State' => 'MO', 													// State or Province
			   'PostalCode' => '64111', 												// Postal code
			   'CountryCode' => 'US'												// Required.  The country code.
			   );
					
$BusinessAddress = array(
					   'Line1' => '123 Test Ave.', 													// Required.  Street address.
					   'Line2' => '', 													// Street address 2.
					   'City' => 'Grandview', 													// Required.  City
					   'State' => 'MO', 													// State or Province
					   'PostalCode' => '64030', 												// Postal code
					   'CountryCode' => 'US'												// Required.  The country code.
					   );
					   
$PrinciplePlaceOfBusinessAddress = array(
					   'Line1' => '1503 Main St.', 													// Required.  Street address.
					   'Line2' => '376', 													// Street address 2.
					   'City' => 'Kansas City', 													// Required.  City
					   'State' => 'MO', 													// State or Province
					   'PostalCode' => '64111', 												// Postal code
					   'CountryCode' => 'US'												// Required.  The country code.
					   );

$BusinessStakeHolder = array(
							'DateOfBirth' => '1982-04-09Z', 										// The date of birth of the stakeholder in the business.  Format:  YYYY-MM-DDZ  (ie. 1970-01-01Z)
							'FullLegalName' => 'Tester Testerson', 										// The legal name of the stakeholder in the business for which the account is being created. 
							'Salutation' => '', 											// A saluation for the account holder.
							'FirstName' => 'Tester', 											// Required.  First name of the account holder.
							'MiddleName' => '', 										// Middle name of the account holder.
							'LastName' => 'Testerson', 											// Required.  Last name of the account holder.
							'Suffix' => '',  											// Suffix name for the account holder.
							'Role' => 'CHAIRMAN', 												// The role of the stakeholder in the business.  Values are:  CHAIRMAN, SECRETARY, TREASURER, BENEFICIAL_OWNER, PRIMARY_CONTACT, INDIVIDUAL_PARTNER, NON_INDIVIDUAL_PARTNER, PRIMARY_INDIVIDUAL_PARTNER, DIRECTOR, NO_BENEFICIAL_OWNER
							'CountryCode' => 'US'											// The country code of the stakeholder's address.
							);

$BusinessStakeHolderAddress = array(
					   'Line1' => '1503 Main St.', 													// Required.  Street address.
					   'Line2' => '376', 													// Street address 2.
					   'City' => 'Kansas City', 													// Required.  City
					   'State' => 'MO', 													// State or Province
					   'PostalCode' => '64111', 												// Postal code
					   'CountryCode' => 'US'												// Required.  The country code.
					   );

$PayPalRequestData = array(
						   'CreateAccountFields' => $CreateAccountFields, 
						   'Address' => $Address, 
						   'BusinessAddress' => $BusinessAddress, 
						   'PrinciplePlaceOfBusinessAddress' => $PrinciplePlaceOfBusinessAddress, 
						   'BusinessStakeHolder' => $BusinessStakeHolder, 
						   'BusinessStakeHolderAddress' => $BusinessStakeHolderAddress
						   );

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->CreateAccount($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>