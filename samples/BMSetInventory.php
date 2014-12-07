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
$BMSetInventoryFields = array(
    'hostedbuttonid' => 'EJKWK4LL36ACE', 			// Required.  The ID of the hosted button whose inventory you want to set.
    'trackinv' => '1',                   // Required.  Whether to track inventory levels associated with the button.  Values are:  0 - do not track, 1 - track
    'trackpnl' => '1',                   // Required.  Whether to track the gross profit associated with inventory changes.  Values are:  0 - do not track, 1 - track
    'optionnameindex' => '',            // Option index, which identifies the button.  Option index 0 is the menu that contains the price if one exists; otherwise, it is the first menu without a price.
    'soldouturl' => 'http://www.domain.com/soldout',                 // The URL to which the buyer's browser is redreicted when the inventory drops to 0.  This also prevents a sale when the inventory drops to 0.
    'reusedigitaldownloadkeys' => '0',   // Whether to reuse download keys.  Values are:  0 - do not reuse keys (default), 1 - reuse keys.
    'appenddigitaldownloadkeys' => '1',  // Whether to append download keys.  Values are:  0 - do not append keys (defeault), 1 - append keys.  If you do not append, unused keys will be replaced.

);

// One or more digital downloads keys, up to a max of 1k.
$DigitalDownloadKeys = array(
    'key1',
    'key2',
    'etc',
);

$ItemTrackingDetails = array(
    'itemnumber' => '123ABC',                 // The ID for an item associated with this button.
    'itemqty' => '25',                    // The qty you want to specify for the item associated with t his button.  specify either the absolute quantity in this field or the change in qty in the qty delta field
    'itemqtydelta' => '',               // The change in qty  you want to specify for the item associated with this button.  Specify either the change in qty in this field or the absolute qty in the item qty field.
    'itemalert' => '5',                  // The qty of the item associated with this button below which PayPal sends you an email notification.
    'itemcost' => '1.00',                   // The cost of the item associated with this button.
);

// Here we can have up to 10 $OptionTrackingDetail arrays loaded into $OptionTrackingDetails
$OptionTrackingDetails = array();
$OptionTrackingDetail = array(
    'number' => '123ABC-Blue',                     // The menu item's ID for an option in the dropdown menu.
    'qty' => '10',                        // The qty you want to specify for the option associated with this menu item.
    'select' => 'Blue',                     // The menu item's name in a dropdown menu.
    'qtydelta' => '',                   // The change in qty you want to specify for tehe option associated with this menu item.
    'alert' => '3',                      // The qty of the option associated with this menu item below which PayPal sends you an email notification.
    'cost' => '1.00',                       // The cost of the option associated with this menu item.
);
array_push($OptionTrackingDetails, $OptionTrackingDetail);

$OptionTrackingDetail = array(
    'number' => '123ABC-Red',                     // The menu item's ID for an option in the dropdown menu.
    'qty' => '10',                        // The qty you want to specify for the option associated with this menu item.
    'select' => 'Red',                     // The menu item's name in a dropdown menu.
    'qtydelta' => '',                   // The change in qty you want to specify for tehe option associated with this menu item.
    'alert' => '3',                      // The qty of the option associated with this menu item below which PayPal sends you an email notification.
    'cost' => '1.00',                       // The cost of the option associated with this menu item.
);
array_push($OptionTrackingDetails, $OptionTrackingDetail);

$OptionTrackingDetail = array(
    'number' => '123ABC-Green',                     // The menu item's ID for an option in the dropdown menu.
    'qty' => '5',                        // The qty you want to specify for the option associated with this menu item.
    'select' => 'Green',                     // The menu item's name in a dropdown menu.
    'qtydelta' => '',                   // The change in qty you want to specify for tehe option associated with this menu item.
    'alert' => '3',                      // The qty of the option associated with this menu item below which PayPal sends you an email notification.
    'cost' => '1.00',                       // The cost of the option associated with this menu item.
);
array_push($OptionTrackingDetails, $OptionTrackingDetail);
				
$PayPalRequestData = array(
    'BMSetInventoryFields' => $BMSetInventoryFields,
    'DigitalDownloadKeys' => $DigitalDownloadKeys,
    'ItemTrackingDetailsFields' => $ItemTrackingDetails,
    'OptionTrackingDetailsFields' => $OptionTrackingDetails,
);

// Pass data into class for processing with PayPal and load the response array into $PayPalResult
$PayPalResult = $PayPal->BMSetInventory($PayPalRequestData);

// Write the contents of the response array to the screen for demo purposes.
echo '<pre />';
print_r($PayPalResult);