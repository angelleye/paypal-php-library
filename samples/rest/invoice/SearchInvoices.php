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

$parameters = array(
    'email'                   => '',             // The initial letters of the email address.
    'recipient_first_name'    => '' ,            // The initial letters of the recipient's first name.
    'recipient_last_name'     => '',             // The initial letters of the recipient last name.
    'recipient_business_name' => '',             // The initial letters of the recipient business name.
    'number'                  => '',               // The invoice number.    
    'start_invoice_date'      => '2010-05-10 PST',             // The start date for the invoice. Date format is yyyy-MM-dd z, as defined in Internet Date/Time Format.
    'end_invoice_date'        => '2019-12-25 PST',             // The end date for the invoice. Date format is yyyy-MM-dd z, as defined in Internet Date/Time Format.
    'start_due_date'          => '',             // The start due date for the invoice. Date format is *yyyy*-*MM*-*dd* *z*, as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).
    'end_due_date'            => '',             // The end due date for the invoice. Date format is *yyyy*-*MM*-*dd* *z*, as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).  
    'start_payment_date'      => '',             // The start payment date for the invoice. Date format is *yyyy*-*MM*-*dd* *z*, as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6). 
    'end_payment_date'        => '',             // The end payment date for the invoice. Date format is *yyyy*-*MM*-*dd* *z*, as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).
    'start_creation_date'     => '',             // The start creation date for the invoice. Date format is *yyyy*-*MM*-*dd* *z*, as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).
    'end_creation_date'       => '',             // The end creation date for the invoice. Date format is *yyyy*-*MM*-*dd* *z*, as defined in [Internet Date/Time Format](http://tools.ietf.org/html/rfc3339#section-5.6).    
    'page'                    => '1',            // The offset for the search results.
    'page_size'               => '20',           // The page size for the search results.  
    'total_count_required'    => 'true',         // Indicates whether the total count appears in the response. Default is `false`.    
    'archived'                => '',             // A flag indicating whether search is on invoices archived by merchant. true - returns archived / false returns unarchived / null returns all.    
);

$returnArray = $PayPal->SearchInvoices($parameters);
echo "<pre>";
print_r($returnArray);
