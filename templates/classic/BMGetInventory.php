<?php
// Include required library files.
require_once('../includes/config.php');
require_once('../autoload.php');

// Create PayPal object.
$PayPalConfig = array(
					'Sandbox' => $sandbox,
					'APIUsername' => $api_username,
					'APIPassword' => $api_password,
					'APISignature' => $api_signature, 
					'PrintHeaders' => $print_headers, 
					'LogResults' => $log_results,
					'LogPath' => $log_path,
					);

$PayPal = new angelleye\PayPal\PayPal($PayPalConfig);

// Prepare request arrays
$BMGetInventoryFields = array
						(
						'hostedbuttonid' => '', 			// Required.  The ID of the hosted button whose inventory information you want to obtain.
						);

// One or more digital download keys up to a max of 1k.
$DigitalDownloadKeys = array(
    'key1',
    'key2',
    'etc',
);
				
$PayPalRequestData = array(
    'BMGetInventoryFields' => $BMGetInventoryFields,
    'DigitalDownloadKeys' => $DigitalDownloadKeys
);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->BMGetInventory($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);