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
$UpdateInvoiceFields = array(
							'InvoiceID' => 'INV2-3283-QFCE-5L2E-4FCH', 					// Required.  ID of the invoice to update.
							'MerchantEmail' => 'sandbox@angelleye.com', 				// Required.  Merchant email address.
							'PayerEmail' => 'sandbo_1204199080_biz@angelleye.com', 				// Required.  Payer email address.
							'Number' => '', 					// Unique ID for the invoice.
							'CurrencyCode' => 'USD', 				// Required.  Currency used for all invoice item amounts and totals.
							'InvoiceDate' => '', 				// Date on which the invoice is enabled.
							'DueDate' => '', 					// Date on which the invoice payment is due.
							'PaymentTerms' => 'Net45', 				// Required.  Terms by which the invoice payment is due.  Values are:  DueOnReceipt, DueOnSpecified, Net10, Net15, Net30, Net45
							'DiscountPercent' => '', 			// Discount percent applied to the invoice.
							'DiscountAmount' => '', 			// Discount amount applied to the invoice.  If DiscountPercent is provided, DiscountAmount is ignored.
							'Terms' => '', 						// General terms for the invoice.
							'Note' => 'Updated the note.', 						// Note to the payer company.
							'MerchantMemo' => '', 				// Memo for bookkeeping that is private to the merchant.
							'ShippingAmount' => '', 			// Cost of shipping
							'ShippingTaxName' => '', 			// Name of the applicable tax on the shipping cost.
							'ShippingTaxRate' => '', 			// Rate of the applicable tax on the shipping cost.
							'LogoURL' => ''						// Complete URL to an external image used as the logo, if any.
							);
							
$BusinessInfo = array(
					'FirstName' => '', 							// First name of the company contact.
					'LastName' => '', 							// Last name of the company contact.
					'BusinessName' => '', 						// Company business name.
					'Phone' => '', 								// Phone number for contacting the company.
					'Fax' => '', 								// Fax number used by the company.
					'Website' => '', 							// Website used by the company.
					'Custom' => '' 								// Custom value to be displayed in the contact information details.
					);
					
$BusinessInfoAddress = array(
							'Line1' => '', 						// Required. First line of address.
							'Line2' => '', 						// Second line of address.
							'City' => '', 						// Required. City of thte address.
							'State' => '', 						// State for the address.
							'PostalCode' => '', 				// Postal code of the address
							'CountryCode' => ''					// Required.  Country code of the address.
							);

$BillingInfo = array(
					'FirstName' => '', 							// First name of the company contact.
					'LastName' => '', 							// Last name of the company contact.
					'BusinessName' => '', 						// Company business name.
					'Phone' => '', 								// Phone number for contacting the company.
					'Fax' => '', 								// Fax number used by the company.
					'Website' => '', 							// Website used by the company.
					'Custom' => '' 								// Custom value to be displayed in the contact information details.
					);
					
$BillingInfoAddress = array(
						'Line1' => '123 Test Ave.', 						// Required. First line of address.
						'Line2' => '', 						// Second line of address.
						'City' => 'Kansas City', 						// Required. City of thte address.
						'State' => 'MO', 						// State for the address.
						'PostalCode' => '64111', 				// Postal code of the address
						'CountryCode' => 'US'					// Required.  Country code of the address.
						);

$ShippingInfo = array(
					'FirstName' => '', 							// First name of the company contact.
					'LastName' => '', 							// Last name of the company contact.
					'BusinessName' => '', 						// Company business name.
					'Phone' => '', 								// Phone number for contacting the company.
					'Fax' => '', 								// Fax number used by the company.
					'Website' => '', 							// Website used by the company.
					'Custom' => '' 								// Custom value to be displayed in the contact information details.
					);
					
$ShippingInfoAddress = array(
						'Line1' => '', 						// Required. First line of address.
						'Line2' => '', 						// Second line of address.
						'City' => '', 						// Required. City of thte address.
						'State' => '', 						// State for the address.
						'PostalCode' => '', 				// Postal code of the address
						'CountryCode' => ''					// Required.  Country code of the address.
						);

// For invoice items you populate a nested array with multiple $InvoiceItem arrays.  Normally you'll be looping through cart items to populate the $InvoiceItem 
// array and then push it into the $InvoiceItems array at the end of each loop for an entire collection of all items in $InvoiceItems.

$InvoiceItems = array();

$InvoiceItem = array(
					'Name' => '', 							// Required.  SKU or name of the item.
					'Description' => '', 					// Item description.
					'Date' => '', 							// Date on which the product or service was provided.
					'Quantity' => '', 						// Required.  Item count.  Values are:  0 to 10000
					'UnitPrice' => '', 						// Required.  Price of the item, in the currency specified by the invoice.
					'TaxName' => '', 						// Name of the applicable tax.
					'TaxRate' => ''							// Rate of the applicable tax.
					);
array_push($InvoiceItems,$InvoiceItem);

$PayPalRequestData = array(
						   'UpdateInvoiceFields' => $UpdateInvoiceFields, 
						   //'BusinessInfo' => $BusinessInfo, 
						   //'BusinessInfoAddress' => $BusinessInfoAddress 
						   'BillingInfo' => $BillingInfo, 
						   'BillingInfoAddress' => $BillingInfoAddress 
						   //'ShippingInfo' => $ShippingInfo, 
						   //'ShippingInfoAddress' => $ShippingInfoAddress, 
						   //'InvoiceItems' => $InvoiceItems
						   );

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->UpdateInvoice($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);
?>