<?php
// Include required library files.
require_once('../../includes/config.php');
require_once('../../autoload.php');


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
$AddBankAccountFields = array(
							'AccountHolderDateOfBirth' => '', 									// The date of birth of the account holder.  Format:  YYYY-MM-DDZ (ie. 1970-01-01Z)
							'AccountID' => '', 													// The ID number of the PayPal account for which a bank account is added.  You must specify either AccountID or EmailAddress for this request.
							'AgencyNumber' => '', 												// For the Brazil Agency Number
							'BankAccountNumber' => '', 											// The account number (BBAN) of the bank account to be added.
							'BankAccountType' => '', 											// The type of bank account to be added.  Values are:  CHECKING, SAVINGS, BUSINESS_SAVINGS, BUSINESS_CHECKING, NORMAL, UNKNOWN
							'BankCode' => '', 													// The code that identifies the bank where the account is held.
							'BankCountryCode' => '', 											// Required.  The country code of the bank.
							'BankName' => '', 													// The name of the bank.  
							'BankTransitNumber' => '', 											// The transit number of the bank.
							'BranchCode' => '', 												// The branch code for the bank.
							'BranchLocation' => '', 											// The branch location.
							'BSBNumber' => '', 													// The Bank/State/Branch number for the bank.
							'CLABE' => '', 														// CLABE represents the bank information for countries like Mexico.
							'ConfirmationType' => '', 											// Required.  Whether PayPal account holders are redirected to PayPal.com to confirm the bank account addition.  When you pass NONE for this param, the addition is made without the account holder's explicit confirmation.  If you pass WEB, a URL is returned.  Values are:  WEB, NONE.  NONE requires advanced permissions.
							'ControlDigit' => '', 												// The control digits for the bank.
							'EmailAddress' => '', 												// The email address of the PayPal account holder.  You must specify either AccountID or EmailAddress.
							'IBAN' => '', 														// The IBAN for the bank.
							'InstitutionNumber' => '', 											// The institution number for the bank.
							'PartnerInfo' => '', 												// The partner information for the bank.
							'RibKey' => '', 													// The RIB Key for the bank
							'RoutingNumber' => '', 												// The bank's routing number.
							'SortCode' => '', 													// The branch sort code.
							'TaxIDType' => '', 													// Tax ID type of CNPJ or CPF, only supported for Brazil
							'TaxIDNumber' => '' 												// Tax ID number for Brazil
							);
							
$WebOptions = array(
					'CancelURL' => '', 															// The URL to which the user is returned when they cancel the flow at PayPal.com
					'CancelURLDescription' => '', 												// A description for the CancelURL
					'ReturnURL' => '', 															// The URL to which the user is returned when they complete the process.
					'ReturnURLDescription' => ''												// A description for the ReturnURL
					);

$PayPalRequestData = array(
						   'AddBankAccountFields' => $AddBankAccountFields, 
						   'WebOptions' => $WebOptions
						   );

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->AddBankAccount($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
