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
$SearchInvoicesFields = array(
							'MerchantEmail' => 'sandbo_1215254764_biz@angelleye.com', 			// Required.  Email address of invoice creator.
							'Page' => '1', 					// Required.  Page number of result set, starting with 1
							'PageSize' => '1'				// Required.  Number of result pages, between 1 and 100
							);

$Parameters = array(
					'Email' => '', 															// Email search string
					'RecipientName' => '', 													// Recipient search string
					'BusinessName' => '', 													// Company search string
					'InvoiceNumber' => '', 													// Invoice number search string
					'Status' => '', 														// Invoice status search
					'LowerAmount' => '', 													// Invoice amount search.  It specifies the smallest amount to be returned.  If you pass a value for this field, you must also pass a CurrencyCode value.
					'UpperAmount' => '', 													// Invoice amount search.  It specifies the largest amount to be returned.  If you pass a value for this field, you must also pass a CurrencyCode value.
					'CurrencyCode' => '', 													// Currency used for lower and upper amounts.  
					'Memo' => '', 															// Invoice memo search string
					'Origin' => '' 														// Indicates whether the invoice was created by the website or by an API call.  Values are:  Web, API
					//'InvoiceDate' => array('StartDate' => '', 'EndDate' => ''), 			// Invoice date range filter
					//'DueDate' => array('StartDate' => '', 'EndDate' => ''), 				// Invoice due Date range filter
					//'PaymentDate' => array('StartDate' => '', 'EndDate' => ''), 			// Invoice payment date range filter.
					//'CreationDate' => array('StartDate' => '', 'EndDate' => '')				// Invoice creation date range filter.
					);

$PayPalRequestData = array(
						   'SearchInvoicesFields' => $SearchInvoicesFields, 
						   'Parameters' => $Parameters
						   );

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->SearchInvoices($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
