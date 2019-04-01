<?php

namespace angelleye\PayPal\rest\invoice;

/**
 *	An open source PHP library written to easily work with PayPal's API's
 *	
 *	Email:  service@angelleye.com
 *  Facebook: angelleyeconsulting
 *  Twitter: angelleye
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 * @package			paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 * @link			https://github.com/angelleye/paypal-php-library/
 * @website			http://www.angelleye.com
 * @support         http://www.angelleye.com/product/premium-support/
 * @version			v2.0.4
 * @filesource
*/

use PayPal\Api\Address;
use PayPal\Api\Amount;
use PayPal\Api\BillingInfo;
use PayPal\Api\CancelNotification;
use PayPal\Api\Cost;
use PayPal\Api\Currency;
use PayPal\Api\FileAttachment;
use PayPal\Api\Invoice;
use PayPal\Api\InvoiceAddress;
use PayPal\Api\InvoiceItem;
use PayPal\Api\MerchantInfo;
use PayPal\Api\Notification;
use PayPal\Api\PaymentTerm;
use PayPal\Api\PaymentDetail;
use PayPal\Api\Phone;
use PayPal\Api\Participant;
use PayPal\Api\RefundDetail;
use PayPal\Api\Search;
use PayPal\Api\ShippingInfo;
use PayPal\Api\ShippingCost;
use PayPal\Api\Tax;
use PayPal\Api\Template;
use PayPal\Api\Templates;
use PayPal\Api\TemplateData;
use PayPal\Api\TemplateSettings;
use PayPal\Api\TemplateSettingsMetadata;
use \angelleye\PayPal\RestClass;

/**
 * InvoiceAPI.
 * This class is responsible for Invoice APIs & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class InvoiceAPI extends RestClass {

    /**
     * Private vairable to fetch and return @PayPal\Rest\ApiContext object.
     *
     * @var \PayPal\Rest\ApiContext $_api_context 
     */ 
    private $_api_context;

    /**
	 * Constructor
	 *
	 * @access	public
	 * @param	mixed[]	$configArray Array structure providing config data
	 * @return	void
	 */
    public function __construct($configArray) {        
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }
    
    /**
     *  Creates an invoice. Include invoice details including merchant information in the request.
     *
     * @param Array $requestData
     * @return Array|Object
     */
    public function CreateInvoice($requestData,$third_party=false,$refesh_token=''){
            try {
                    $invoice = new Invoice();
                    
                    // ### Setting Merchant info to invoice object.
                    // ### Start
                    $MerchantInfo = new MerchantInfo();
                    if( isset($requestData['merchantInfo'])){
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['merchantInfo']), $MerchantInfo);
                        if( isset($requestData['merchantPhone'])){
                            $merchantPhone = new Phone();
                            $this->setArrayToMethods($this->checkEmptyObject($requestData['merchantPhone']), $merchantPhone);
                            $MerchantInfo->setPhone($merchantPhone);
                        }
                        if(isset($requestData['merchantAddress'])){
                            $merchantAddress = new Address();
                            $this->setArrayToMethods($this->checkEmptyObject($requestData['merchantAddress']), $merchantAddress);
                            $MerchantInfo->setAddress($merchantAddress);
                        }
                        $invoice->setMerchantInfo($MerchantInfo);
                    }
                    // ### End
                    
                    // ### Setting cc_info
                    // ### Start
                    $Participant = new Participant();
                    if(isset($requestData['ccInfo'])){
                       $this->setArrayToMethods($this->checkEmptyObject($requestData['ccInfo']), $Participant);
                        $participantArray = $this->checkEmptyObject((array)$Participant);
                        if (!empty($participantArray)) {
                            $invoice->setCcInfo(array($Participant));
                        }
                    }
                    // ### End
                    
                    // ### Setting Minimum Amount Due
                    // ### Start                            
                    $MinAmountCurrency = new Currency();
                    if(isset($requestData['MinimumAmountDue'])){
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['MinimumAmountDue']), $MinAmountCurrency);
                    }
                    $MinAmountCurrencyArray = $this->checkEmptyObject((array)$MinAmountCurrency);
                    if (!empty($MinAmountCurrencyArray)) {
                        $invoice->setMinimumAmountDue($MinAmountCurrency);
                    }
                    // ### End
                    
                    // ### Setting Billing Info to invoice object. 
                    // ### Start
                    $BillingInfo = new BillingInfo();
                    if(isset($requestData['billingInfo'])){
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['billingInfo']), $BillingInfo);
                    }
                    if (isset($requestData['billingInfoAddress'])) {
                        $InvoiceAddress = new InvoiceAddress();
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['billingInfoAddress']), $InvoiceAddress);
                        $BillingInfo->setAddress($InvoiceAddress);
                    }
                    if (isset($requestData['billingInfoPhone'])) {
                        $billingPhone = new Phone();
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['billingInfoPhone']), $billingPhone);
                        $BillingInfo->setPhone($billingPhone);
                    }

                    $BillingInfoArray = $this->checkEmptyObject((array)$BillingInfo);
                    if (!empty($BillingInfoArray)) {
                        $invoice->setBillingInfo(array($BillingInfo));                        
                    }
                    //End
                    
                    // ### Add items in Invoice object.
                    // ### Start.    
                   if(isset($requestData['itemArray'])) {
                       $itemArray = array();
                       foreach ($requestData['itemArray'] as $item) {
                           $InvoiceItem = new InvoiceItem();

                           if (isset($item['UnitPrice']) && count(array_filter($item['UnitPrice'])) > 0) {
                               $ItemCurrency = new Currency();
                               $this->setArrayToMethods(array_filter($item['UnitPrice']), $ItemCurrency);
                               $InvoiceItem->setUnitPrice($ItemCurrency);
                           }
                           unset($item['UnitPrice']);
                           if (isset($item['Tax']) && count(array_filter($item['Tax'])) > 0) {
                               $ItemTax = new Tax();
                               $this->setArrayToMethods(array_filter($item['Tax']), $ItemTax);
                               $InvoiceItem->setTax($ItemTax);
                           }
                           unset($item['Tax']);
                           if (isset($item['Discount']) && count(array_filter($item['Discount'])) > 0) {
                               $ItemCost = new Cost();
                               $this->setArrayToMethods(array_filter($item['Discount']), $ItemCost);
                               $InvoiceItem->setDiscount($ItemCost);
                           }
                           unset($item['Discount']);

                           $this->setArrayToMethods(array_filter($item), $InvoiceItem);
                           array_push($itemArray, $InvoiceItem);
                       }
                       $itemArrayData = $this->checkEmptyObject($itemArray);
                       if (!empty($itemArrayData)) {
                           $invoice->setItems($itemArray);
                       }
                   }
                    // ### END
                    
                    // #### Final Discount
                    // You can add final discount to the invoice as shown below. You could either use "percent" or "value" when providing the discount
                    
                    if(isset($requestData['finalDiscountForInvoice']) && $requestData['finalDiscountForInvoice']['type']  == 'Percent'){
                        $FinalDiscountCost = new Cost();
                        $FinalDiscountCost->setPercent($requestData['finalDiscountForInvoice']['Percent']);                        
                        $invoice->setDiscount($FinalDiscountCost);   
                    }
                    if(isset($requestData['finalDiscountForInvoice']) && $requestData['finalDiscountForInvoice']['type']  == 'Amount'){
                        $FinalDiscountCost = new Cost();
                        $discountCurrency = new Currency();
                        $discountCurrency->setCurrency($requestData['finalDiscountForInvoice']['Amount']['Currency']);
                        $discountCurrency->setValue($requestData['finalDiscountForInvoice']['Amount']['Value']);
                        $FinalDiscountCost->setAmount($discountCurrency);
                        $invoice->setDiscount($FinalDiscountCost);
                    }
                    
                    if(isset($requestData['paymentTerm']) && count(array_filter($requestData['paymentTerm'])) > 0){
                        $PaymentTerm = new PaymentTerm();
                        $this->setArrayToMethods(array_filter($requestData['paymentTerm']), $PaymentTerm);
                        $invoice->setPaymentTerm($PaymentTerm);
                    }
                    
                    // ### Shipping Information
                    // ### Start
                    $ShippingInfo = new ShippingInfo();
                    if (isset($requestData['shippingInfo'])) {
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['shippingInfo']), $ShippingInfo);
                    }
                    if (isset($requestData['shippingInfoPhone'])) {
                        $ShippingInfoPhone = new Phone();
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['shippingInfoPhone']), $ShippingInfoPhone);
                        $ShippingInfo->setPhone($ShippingInfoPhone);
                    }                    
                    if (isset($requestData['shippingInfoAddress'])) {
                        $ShippingInfoInvoiceAddress = new InvoiceAddress();
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['shippingInfoAddress']), $ShippingInfoInvoiceAddress);
                        $ShippingInfo->setAddress($ShippingInfoInvoiceAddress);
                    }

                    $shippingInfoArray = $this->checkEmptyObject((array)$ShippingInfo);
                    if (!empty($shippingInfoArray)) {
                        $invoice->setShippingInfo($ShippingInfo);
                    }
                    if (isset($requestData['invoiceData'])) {
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['invoiceData']), $invoice);
                    }
                    
                    if(isset($requestData['shippingCost']['type']) && $requestData['shippingCost']['type'] == 'Amount'){
                        $shippingCurrency = new Currency();
                        $shippingCurrency->setCurrency($requestData['shippingCost']['Amount']['Currency']);
                        $shippingCurrency->setValue($requestData['shippingCost']['Amount']['Value']);
                        $ShippingCost = new ShippingCost();
                        $ShippingCost->setAmount($shippingCurrency);
                        $invoice->setShippingCost($ShippingCost);       
                    }

                    
                    if(isset($requestData['attachments']) && count(array_filter($requestData['attachments'])) > 0){
                        foreach ($requestData['attachments'] as $value) {
                            $attachment = new FileAttachment();
                            $attachment->setName($value['Name']);
                            $attachment->setUrl($value['url']);
                            $invoice->setAttachments(array($attachment));
                        }                        
                    }
                                                                                
                // ### Create Invoice
                // Create an invoice by calling the invoice->create() method
                if($third_party === true  && !empty($refesh_token)){
                    $invoice->updateAccessToken($refesh_token, $this->_api_context);
                }
                $requestArray = clone $invoice;
                $invoice->create($this->_api_context);
                $returnArray['RESULT'] = 'Success';
                $returnArray['INVOICE']=$invoice->toArray();                
                $returnArray['RAWREQUEST']=$requestArray->toJSON();
                $returnArray['RAWRESPONSE']=$invoice->toJSON();
                return $returnArray;
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
                return $this->createErrorResponse($ex);
            }        
    }        
    
    /**
     * Lists some or all merchant invoices. Filters the response by any specified optional query string parameters.
     *
     * @param Array $params
     * @return Array|Object
     */
    public function ListInvoice($params,$third_party=false,$refesh_token=''){
        try {
            $apiContext = $this->_api_context;
            if($third_party === true  && !empty($refesh_token)){
                $apiContext->getCredential()->updateAccessToken($apiContext->getConfig(), $refesh_token);
            }
            $invoices = Invoice::getAll(array_filter($params), $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICES'] = $invoices->toArray();
            return $returnArray;
            
        }  catch (\PayPal\Exception\PayPalConnectionException $ex) {
               return $this->createErrorResponse($ex);
        }
    }

    /**
     * Gets the details for a specified invoice, by ID.
     *
     * @param string $invoiceId
     * @return Array|Object
     */
    public function GetInvoice($invoiceId){
        try {
            $invoice = Invoice::get($invoiceId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICE'] = $invoice->toArray();
            $returnArray['RAWREQUEST']='{id:'.$invoiceId.'}';
            $returnArray['RAWRESPONSE']=$invoice->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Creates third party invoice on someone else's behalf.
     * This requires using `Obtain User's Consent` to fetch the refresh token of the third party merchant.     
     *  
     * @param string $invoiceId
     * @param string $refreshToken
     * @return Array|Object
     */
    public function GetThirdPartyInvoice($invoiceId,$refreshToken){
        try {          
            $apiContext = $this->_api_context;
            $apiContext->getCredential()->updateAccessToken($apiContext->getConfig(), $refreshToken);
            $invoice = Invoice::get($invoiceId, $apiContext);
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICE'] = $invoice->toArray();
            $returnArray['RAWREQUEST']='{id:'.$invoiceId.'}';
            $returnArray['RAWRESPONSE']=$invoice->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Cancels an invoice, by ID.
     *
     * @param Array $cancelNotification
     * @param string $InvoiceID
     * @return boolean
     */
    public function CancelInvoice($cancelNotification,$InvoiceID,$third_party=false,$refesh_token=''){
        try {
            $notify = new CancelNotification();
            $this->setArrayToMethods(array_filter($cancelNotification), $notify);
            
            $invoice = new Invoice();
            $invoice->setId($InvoiceID);
            if($third_party === true  && !empty($refesh_token)){
                $invoice->updateAccessToken($refesh_token, $this->_api_context);
            }
            $requestArray = clone $invoice;
            $cancelStatus = $invoice->cancel($notify, $this->_api_context);
                        
            $returnArray['RESULT'] = 'Success';
            $returnArray['CANCEL_STATUS'] = $cancelStatus;
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$cancelStatus;
            return $returnArray;                   
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    /**
     * Marks the status of a specified invoice, by ID, as refunded.
     *
     * @param Array $requestData
     * @return Array|Object
     */
    public function RecordRefund($requestData,$third_party=false,$refesh_token=''){
        try {
            $invoice  = new Invoice();
            $invoice->setId($requestData['invoiceId']);
            if($third_party === true  && !empty($refesh_token)){
                $invoice->updateAccessToken($refesh_token, $this->_api_context);
            }
            $refund = new RefundDetail();
            if(isset($requestData['refundDetail'])){
                $this->setArrayToMethods($requestData['refundDetail'], $refund);
            }
            
            if(isset($requestData['amount']) && count(array_filter($requestData['amount'])) > 0){
                $amt= new Currency(json_encode($requestData['amount']));
                $refund->setAmount($amt);
            }
            $requestArray = clone $invoice;
            $refundStatus = $invoice->recordRefund($refund, $this->_api_context);
            $invoice = Invoice::get($requestData['invoiceId'], $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['REFUND_STATUS'] = $refundStatus;
            $returnArray['INVOICE'] = $invoice->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$invoice->toJSON();
            return $returnArray;                                                
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Sends a reminder to the payer about an invoice, by ID.
     *
     * @param Array $remindNotification
     * @param string $InvoiceID
     * @return Array|Object
     */
    public function RemindInvoice($remindNotification,$InvoiceID,$third_party=false,$refesh_token=''){
        try {
            $apiContext = $this->_api_context;
            if($third_party === true  && !empty($refesh_token)){
                $apiContext->getCredential()->updateAccessToken($apiContext->getConfig(), $refesh_token);
            }
            $invoice  = new Invoice();
            $invoice->setId($InvoiceID);
            $notify = new Notification();
            $this->setArrayToMethods(array_filter($remindNotification), $notify);
            $remindStatus = $invoice->remind($notify, $apiContext);
            $requestArray = clone $invoice;
            $invoice = Invoice::get($InvoiceID, $apiContext);
            $returnArray['RESULT'] = 'Success';
            $returnArray['REMIND_STATUS'] = $remindStatus;
            $returnArray['REMIND_INVOICE'] = $invoice->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$invoice->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    /**
     * Generate a QR code for an invoice by passing the invoice ID.
     *
     * @param Array $parameters
     * @param string $InvoiceID
     * @param string $path
     * @return Array|Object
     */
    public function RetrieveQRCode($parameters,$InvoiceID,$path,$third_party=false,$refesh_token=''){
        
        try{
            $apiContext = $this->_api_context;
            if($third_party === true  && !empty($refesh_token)){
                $apiContext->getCredential()->updateAccessToken($apiContext->getConfig(), $refesh_token);
            }
            $image = Invoice::qrCode($InvoiceID, array_filter($parameters), $apiContext);
            $path = $image->saveToFile($path);
            $returnArray['RESULT'] = 'Success';            
            return array('Image' => $image->getImage());
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }                
    }
    
    /**
     * Searches for an invoice or invoices. Include a search object that specifies your search criteria in the request.
     *
     * @param Array $parameters
     * @return Array|Object
     */
    public function SearchInvoices($parameters,$third_party=false,$refesh_token=''){
        
        try{
            $apiContext = $this->_api_context;
            if($third_party === true  && !empty($refesh_token)){
                $apiContext->getCredential()->updateAccessToken($apiContext->getConfig(), $refesh_token);
            }
            $search = new Search(json_encode(array_filter($parameters)));            
            $invoices = Invoice::search($search, $apiContext);
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICES'] = $invoices->toArray();
            $returnArray['RAWREQUEST']=json_encode(array_filter($parameters));
            $returnArray['RAWRESPONSE']=$invoices->toJSON();
            return $returnArray;
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    /**
     * Fully updates an invoice, by ID.
     *
     * @param Array|Object $requestData
     * @return Array|Object
     */
    public function UpdateInvoice($requestData,$third_party=false,$refesh_token=''){
        
        try {       
            $invoice = new Invoice();
            $invoice->setId($requestData['InvoiceID']);
            // ### Setting Merchant info to invoice object.
            // ### Start
            $MerchantInfo = new MerchantInfo();
            if( isset($requestData['merchantInfo'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['merchantInfo']), $MerchantInfo);
                if( isset($requestData['merchantPhone'])){
                    $merchantPhone = new Phone();
                    $this->setArrayToMethods($this->checkEmptyObject($requestData['merchantPhone']), $merchantPhone);
                    $MerchantInfo->setPhone($merchantPhone);
                }
                if(isset($requestData['merchantAddress'])){
                    $merchantAddress = new Address();
                    $this->setArrayToMethods($this->checkEmptyObject($requestData['merchantAddress']), $merchantAddress);
                    $MerchantInfo->setAddress($merchantAddress);
                }
                $invoice->setMerchantInfo($MerchantInfo);
            }
            // ### End

            // ### Setting cc_info
            // ### Start
            $Participant = new Participant();
            if(isset($requestData['ccInfo'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['ccInfo']), $Participant);
                $participantArray = $this->checkEmptyObject((array)$Participant);
                if (!empty($participantArray)) {
                    $invoice->setCcInfo(array($Participant));
                }
            }
            // ### End

            // ### Setting Minimum Amount Due
            // ### Start
            $MinAmountCurrency = new Currency();
            if(isset($requestData['MinimumAmountDue'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['MinimumAmountDue']), $MinAmountCurrency);
            }
            $MinAmountCurrencyArray = $this->checkEmptyObject((array)$MinAmountCurrency);
            if (!empty($MinAmountCurrencyArray)) {
                $invoice->setMinimumAmountDue($MinAmountCurrency);
            }
            // ### End

            // ### Setting Billing Info to invoice object.
            // ### Start
            $BillingInfo = new BillingInfo();
            if(isset($requestData['billingInfo'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['billingInfo']), $BillingInfo);
            }
            if (isset($requestData['billingInfoAddress'])) {
                $InvoiceAddress = new InvoiceAddress();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['billingInfoAddress']), $InvoiceAddress);
                $BillingInfo->setAddress($InvoiceAddress);
            }
            if (isset($requestData['billingInfoPhone'])) {
                $billingPhone = new Phone();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['billingInfoPhone']), $billingPhone);
                $BillingInfo->setPhone($billingPhone);
            }

            $BillingInfoArray = $this->checkEmptyObject((array)$BillingInfo);
            if (!empty($BillingInfoArray)) {
                $invoice->setBillingInfo(array($BillingInfo));
            }
            //End

            // ### Add items in Invoice object.
            // ### Start.
            if(isset($requestData['itemArray'])) {

                $itemArray = array();
                foreach ($requestData['itemArray'] as $item) {
                    $InvoiceItem = new InvoiceItem();

                    if (isset($item['UnitPrice']) && count(array_filter($item['UnitPrice'])) > 0) {
                        $ItemCurrency = new Currency();
                        $this->setArrayToMethods(array_filter($item['UnitPrice']), $ItemCurrency);
                        $InvoiceItem->setUnitPrice($ItemCurrency);
                    }
                    unset($item['UnitPrice']);
                    if (isset($item['Tax']) && count(array_filter($item['Tax'])) > 0) {
                        $ItemTax = new Tax();
                        $this->setArrayToMethods(array_filter($item['Tax']), $ItemTax);
                        $InvoiceItem->setTax($ItemTax);
                    }
                    unset($item['Tax']);
                    if (isset($item['Discount']) && count(array_filter($item['Discount'])) > 0) {
                        $ItemCost = new Cost();
                        $this->setArrayToMethods(array_filter($item['Discount']), $ItemCost);
                        $InvoiceItem->setDiscount($ItemCost);
                    }
                    unset($item['Discount']);

                    $this->setArrayToMethods(array_filter($item), $InvoiceItem);
                    array_push($itemArray, $InvoiceItem);
                }
                $itemArrayData = $this->checkEmptyObject($itemArray);
                if (!empty($itemArrayData)) {
                    $invoice->setItems($itemArray);
                }
            }
            // ### END

            // #### Final Discount
            // You can add final discount to the invoice as shown below. You could either use "percent" or "value" when providing the discount

            if(isset($requestData['finalDiscountForInvoice']) && $requestData['finalDiscountForInvoice']['type']  == 'Percent'){
                $FinalDiscountCost = new Cost();
                $FinalDiscountCost->setPercent($requestData['finalDiscountForInvoice']['Percent']);
                $invoice->setDiscount($FinalDiscountCost);
            }
            if(isset($requestData['finalDiscountForInvoice']) && $requestData['finalDiscountForInvoice']['type']  == 'Amount'){
                $FinalDiscountCost = new Cost();
                $discountCurrency = new Currency();
                $discountCurrency->setCurrency($requestData['finalDiscountForInvoice']['Amount']['Currency']);
                $discountCurrency->setValue($requestData['finalDiscountForInvoice']['Amount']['Value']);
                $FinalDiscountCost->setAmount($discountCurrency);
                $invoice->setDiscount($FinalDiscountCost);
            }
            if(isset($requestData['finalDiscountForInvoice']) && $requestData['finalDiscountForInvoice']['type']  == 'Amount'){
                $FinalDiscountCost = new Cost();
                $discountCurrency = new Currency();
                $discountCurrency->setCurrency($requestData['finalDiscountForInvoice']['Amount']['Currency']);
                $discountCurrency->setValue($requestData['finalDiscountForInvoice']['Amount']['Value']);
                $FinalDiscountCost->setAmount($discountCurrency);
                $invoice->setDiscount($FinalDiscountCost);
            }

            if(isset($requestData['paymentTerm']) && count(array_filter($requestData['paymentTerm'])) > 0){
                $PaymentTerm = new PaymentTerm();
                $this->setArrayToMethods(array_filter($requestData['paymentTerm']), $PaymentTerm);
                $invoice->setPaymentTerm($PaymentTerm);
            }

            // ### Shipping Information
            // ### Start
            $ShippingInfo = new ShippingInfo();
            if (isset($requestData['shippingInfo'])) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['shippingInfo']), $ShippingInfo);
            }
            if (isset($requestData['shippingInfoPhone'])) {
                $ShippingInfoPhone = new Phone();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['shippingInfoPhone']), $ShippingInfoPhone);
                $ShippingInfo->setPhone($ShippingInfoPhone);
            }
            if (isset($requestData['shippingInfoAddress'])) {
                $ShippingInfoInvoiceAddress = new InvoiceAddress();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['shippingInfoAddress']), $ShippingInfoInvoiceAddress);
                $ShippingInfo->setAddress($ShippingInfoInvoiceAddress);
            }

            $shippingInfoArray = $this->checkEmptyObject((array)$ShippingInfo);
            if (!empty($shippingInfoArray)) {
                $invoice->setShippingInfo($ShippingInfo);
            }
            if (isset($requestData['invoiceData'])) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['invoiceData']), $invoice);
            }

            if(isset($requestData['shippingCost']['type']) && $requestData['shippingCost']['type'] == 'Amount'){
                $shippingCurrency = new Currency();
                $this->setArrayToMethods(array_filter($requestData['shippingCost']['Currency']), $shippingCurrency);
                $ShippingCost = new ShippingCost();
                $ShippingCost->setAmount($shippingCurrency);
                $invoice->setShippingCost($ShippingCost);
            }
            if(isset($requestData['attachments']) && count(array_filter($requestData['attachments'])) > 0){
                foreach ($requestData['attachments'] as $key => $value) {
                    $attachment = new FileAttachment();
                    $attachment->setName($value['Name']);
                    $attachment->setUrl($value['Url']);
                    $invoice->setAttachments(array($attachment));
                }
            }
            if(isset($requestData['shippingCost']['type']) && $requestData['shippingCost']['type'] == 'Amount'){
                $shippingCurrency = new Currency();
                $shippingCurrency->setCurrency($requestData['shippingCost']['Amount']['Currency']);
                $shippingCurrency->setValue($requestData['shippingCost']['Amount']['Value']);
                $ShippingCost = new ShippingCost();
                $ShippingCost->setAmount($shippingCurrency);
                $invoice->setShippingCost($ShippingCost);
            }
            $this->setArrayToMethods(array_filter($requestData['invoiceData']), $invoice);            

            if($third_party === true  && !empty($refesh_token)){
                $invoice->updateAccessToken($refesh_token, $this->_api_context);
            }
            $requestArray = clone $invoice;
            $invoice->update($this->_api_context);
            $invoice = Invoice::get($invoice->getId(), $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICE'] = $invoice->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$invoice->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
          return $this->createErrorResponse($ex);
        }
    }

    /**
     * Sends an invoice, by ID, to a customer. 
     *
     * @param string $invoiceId
     * @param boolean $third_party
     * @param  string $refresh_token
     * @return Array|Object
     */
    public function SendInvoice($invoiceId,$third_party=false,$refresh_token=''){
        try {
            $invoice = new Invoice();
            $invoice->setId($invoiceId);
            if($third_party === true  && !empty($refresh_token)){
                $invoice->updateAccessToken($refresh_token, $this->_api_context);
            }
            $sendStatus = $invoice->send($this->_api_context);
            $Getinvoice = Invoice::get($invoice->getId(), $this->_api_context);

            $returnArray['RESULT'] = 'Success';
            $returnArray['SEND_STATUS'] = $sendStatus;
            $returnArray['INVOICE'] = $Getinvoice->toArray();
            $returnArray['RAWREQUEST']='{id:'.$invoiceId.'}';
            $returnArray['RAWRESPONSE']=$Getinvoice->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Deletes invoices in the DRAFT or SCHEDULED state, by ID. 
     *
     * @param string $invoiceId
     * @return Array|Object
     */
    public function DeleteInvoice($invoiceId,$third_party=false,$refesh_token=''){
        
        try{
            $invoice = new Invoice();
            $invoice->setId($invoiceId);
            if($third_party === true  && !empty($refesh_token)){
                $invoice->updateAccessToken($refesh_token, $this->_api_context);
            }
            $deleteStatus = $invoice->delete($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['DELETE_STATUS'] = $deleteStatus;
            $returnArray['RAWREQUEST']='{id:'.$invoiceId.'}';
            $returnArray['RAWRESPONSE']=$deleteStatus;
            return $returnArray;            
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Generates the next invoice number that is available to the merchant.
     * The next invoice number uses the prefix and suffix from the last invoice number and increments the number by one.
     *
     * @return Array|Object
     */
    public function GetNextInvoiceNumber($third_party=false,$refesh_token=''){
        try {
            $apiContext = $this->_api_context;
            if($third_party === true  && !empty($refesh_token)){
                $apiContext->getCredential()->updateAccessToken($apiContext->getConfig(), $refesh_token);
            }
            $number = Invoice::generateNumber($apiContext);
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICE_NUMBER'] = $number->toArray();
            $returnArray['RAWREQUEST']='';
            $returnArray['RAWRESPONSE']=$number->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }
    }
    
    /**
     *Marks the status of a specified invoice, by ID, as paid.
     *
     * @param string $invoiceId
     * @param Array $record
     * @param Array $amount
     * @return Array|Object
     */
    public function RecordPayment($invoiceId,$record,$amount,$third_party=false,$refesh_token=''){
        try{
            $invoice = new Invoice();
            $invoice->setId($invoiceId);
            if($third_party === true  && !empty($refesh_token)){
                $invoice->updateAccessToken($refesh_token, $this->_api_context);
            }
            $PaymentDetail = new PaymentDetail();
            $this->setArrayToMethods(array_filter($record), $PaymentDetail);
            
            if(count(array_filter($amount)) > 0){
                $amt= new Currency(json_encode($amount));
                $PaymentDetail->setAmount($amt);
            }
            
            $recordStatus = $invoice->recordPayment($PaymentDetail, $this->_api_context);
            
            $returnInvoice = Invoice::get($invoiceId, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['RECORD_STATUS'] = $recordStatus;
            $returnArray['INVOICE'] = $returnInvoice->toArray();
            $returnArray['RAWREQUEST']='{id:'.$invoiceId.'}';
            $returnArray['RAWRESPONSE']=$returnInvoice->toJSON();
            return $returnArray;
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    /**
     * Creates an invoice template.
     *
     * @param Array $requestData
     * @return Array|Object
     */
    public function CreateInvoiceTemplate($requestData){
        
        try {
            $invoiceTemplateData = new TemplateData();

            /**
             * Setting Items in Invoice Template
             */
            if(isset($requestData['InvoiceItemArray'])){
                $itemArray = array();

                foreach ($requestData['InvoiceItemArray'] as $item) {
                    $InvoiceItem = new InvoiceItem();

                    if(isset($item['UnitPrice']) && count(array_filter($item['UnitPrice'])) > 0){
                        $ItemCurrency = new Currency();
                        $this->setArrayToMethods(array_filter($item['UnitPrice']), $ItemCurrency);
                        $InvoiceItem->setUnitPrice($ItemCurrency);
                    }
                    unset($item['UnitPrice']);

                    if(isset($item['Tax']) && count(array_filter($item['Tax'])) > 0){
                        $ItemTax = new Tax();
                        $this->setArrayToMethods(array_filter($item['Tax']), $ItemTax);
                        $InvoiceItem->setTax($ItemTax);
                    }
                    unset($item['Tax']);

                    if(isset($item['Discount']) && count(array_filter($item['Discount'])) > 0){
                        $ItemCost = new Cost();
                        $this->setArrayToMethods(array_filter($item['Discount']), $ItemCost);
                        $InvoiceItem->setDiscount($ItemCost);
                    }
                    unset($item['Discount']);

                    if(count(array_filter($item))>0){
                        $this->setArrayToMethods(array_filter($item), $InvoiceItem);
                        array_push($itemArray, $InvoiceItem);
                        $invoiceTemplateData->addItem($InvoiceItem);
                    }
                }
            }


            // ### Setting Merchant info to invoice template object.
            // ### Start
            if(isset($requestData['merchantInfo'])){
                $MerchantInfo = new MerchantInfo();
                if( isset($requestData['merchantInfo']) && count(array_filter($requestData['merchantInfo'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['merchantInfo']), $MerchantInfo);
                }
                $merchantPhone = new Phone();
                if( isset($requestData['merchantPhone']) && count(array_filter($requestData['merchantPhone'])) > 0 ){
                    $this->setArrayToMethods(array_filter($requestData['merchantPhone']), $merchantPhone);
                    $MerchantInfo->setPhone($merchantPhone);
                }
                $merchantAddress = new Address();
                if(isset($requestData['merchantAddress']) && count(array_filter($requestData['merchantAddress'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['merchantAddress']), $merchantAddress);
                    $MerchantInfo->setAddress($merchantAddress);
                }
                $invoiceTemplateData->setMerchantInfo($MerchantInfo);
            }

            
            
            // ### End

            // ### Setting Billing Info to invoice object. 
            // ### Start

            if(isset($requestData['billingInfo'])){
                $BillingInfo = new BillingInfo();
                if( isset($requestData['billingInfo']) && count(array_filter($requestData['billingInfo'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['billingInfo']), $BillingInfo);
                }

                $InvoiceAddress = new InvoiceAddress();
                if(isset($requestData['billingInfoAddress']) && count(array_filter($requestData['billingInfoAddress'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['billingInfoAddress']), $InvoiceAddress);
                    $BillingInfo->setAddress($InvoiceAddress);
                }

                $billingPhone = new Phone();
                if(count(array_filter($requestData['billingInfoPhone'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['billingInfoPhone']), $billingPhone);
                    $BillingInfo->setPhone($billingPhone);
                }
                $invoiceTemplateData->setBillingInfo(array($BillingInfo));
            }
            //End


            // ### Shipping Information
            // ### Start

            if(isset($requestData['shippingInfo'])){
                $ShippingInfo = new ShippingInfo();
                $this->setArrayToMethods(array_filter($requestData['shippingInfo']), $ShippingInfo);

                $ShippingInfoPhone = new Phone();
                if(isset($requestData['shippingInfoPhone']) && count(array_filter($requestData['shippingInfoPhone'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['shippingInfoPhone']), $ShippingInfoPhone);
                    $ShippingInfo->setPhone($ShippingInfoPhone);
                }

                $ShippingInfoInvoiceAddress = new InvoiceAddress();
                if(isset($requestData['shippingInfoAddress']) && count(array_filter($requestData['shippingInfoAddress'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['shippingInfoAddress']), $ShippingInfoInvoiceAddress);
                    $ShippingInfo->setAddress($ShippingInfoInvoiceAddress);
                }
                $invoiceTemplateData->setShippingInfo($ShippingInfo);
            }
            if(isset($requestData['templateData'])){
                if(isset($requestData['templateData']['MinimumAmountDue']) && count(array_filter($requestData['templateData']['MinimumAmountDue'])) > 0){
                    $TemplateMinimumAmountDueCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($requestData['templateData']['MinimumAmountDue']), $TemplateMinimumAmountDueCurrency);
                    $invoiceTemplateData->setMinimumAmountDue($TemplateMinimumAmountDueCurrency);
                }
                unset($requestData['templateData']['MinimumAmountDue']);

                if(isset($requestData['templateData']['TotalAmount']) && count(array_filter($requestData['templateData']['TotalAmount'])) > 0){
                    $TemplateTotalAmountCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($requestData['templateData']['TotalAmount']), $TemplateTotalAmountCurrency);
                    $invoiceTemplateData->setTotalAmount($TemplateTotalAmountCurrency);
                }
                unset($requestData['templateData']['TotalAmount']);
                $this->setArrayToMethods(array_filter($requestData['templateData']), $invoiceTemplateData);
            }

            $templateDataCcInfo = isset($requestData['TemplateDataCcInfo']) ? trim($requestData['TemplateDataCcInfo']) : '';
            if(!empty($templateDataCcInfo)){
                $invoiceTemplateData->addCcInfo($templateDataCcInfo);
            }

            if(isset($requestData['templateDiscount']) && count(array_filter($requestData['templateDiscount'])) > 0){
                $templateDiscountCost = new Cost();
                $this->setArrayToMethods(array_filter($requestData['templateDiscount']), $templateDiscountCost);
                $invoiceTemplateData->setDiscount($templateDiscountCost);
            }

            if(isset($requestData['paymentTerm']) && count(array_filter($requestData['paymentTerm'])) > 0){
                $PaymentTerm = new PaymentTerm();
                $this->setArrayToMethods(array_filter($requestData['paymentTerm']), $PaymentTerm);
                $invoiceTemplateData->setPaymentTerm($PaymentTerm);
            }

            if(isset($requestData['attachments']) && count(array_filter($requestData['attachments'])) > 0){
                $attachment = new FileAttachment();
                $this->setArrayToMethods(array_filter($requestData['attachments']), $attachment);
                $invoiceTemplateData->setAttachments(array($attachment));
            }

             // ### Template Settings    
            $displayPreferences = new TemplateSettingsMetadata();
            if(isset($requestData['TemplateSettingsMetadata']) && count(array_filter($requestData['TemplateSettingsMetadata']))>0){
                $this->setArrayToMethods(array_filter($requestData['TemplateSettingsMetadata']), $displayPreferences);
            }
            
            $settingDate = new TemplateSettings();
            if(isset($requestData['TemplateSettings']) && count(array_filter($requestData['TemplateSettings']))>0){
                $this->setArrayToMethods(array_filter($requestData['TemplateSettings']), $settingDate);
            }

            if(count(array_filter((array)$displayPreferences)) > 0){
                $settingDate->setDisplayPreference($displayPreferences);
            }
            
            // ### Template
            $invoiceTemplate = new Template();
            if(isset($requestData['Template']) && count(array_filter($requestData['Template'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['Template']), $invoiceTemplate);
            }
            
            if(count(array_filter((array)$invoiceTemplateData)) > 0){
                $invoiceTemplate->setTemplateData($invoiceTemplateData);
            }
            
            if(count(array_filter((array)$settingDate)) > 0){
                $invoiceTemplate->addSetting($settingDate);
            }
            $requestArray = clone $invoiceTemplate;
            $invoiceTemplate->create($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['TEMPLATE'] = $invoiceTemplate->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$invoiceTemplate->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Deletes a template, by ID.
     *
     * @param string $template_id
     * @return Array|Object
     */
    public function DeleteInvoiceTemplate($template_id){
        try {
            $template = new Template();
            $template->setTemplateId($template_id);
            $deleteStatus = $template->delete($this->_api_context);                        
            $returnArray['RESULT'] = 'Success';
            $returnArray['DELETE_STATUS'] = $deleteStatus;
            $returnArray['RAWREQUEST']='{id:'.$template_id.'}';
            $returnArray['RAWRESPONSE']=$deleteStatus;
            return $returnArray;                                                     
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Lists merchant-created templates with associated details
     *
     * @param Array $fields
     * @return Array|Object
     */
    public function GetAllInvoiceTemplates($fields){
        
        try {
            $templates = Templates::getAll($fields, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICE_TEMPLATES'] = $templates->toArray();
            $returnArray['RAWREQUEST']= json_encode($fields);
            $returnArray['RAWRESPONSE']=$templates->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Shows details for a template, by ID.
     *
     * @param string $templateId
     * @return Array|Object
     */
    public function GetInvoiceTemplate($templateId){
        try {
            $template = Template::get($templateId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['TEMPLATE'] = $template->toArray();
            $returnArray['RAWREQUEST']='{id:'.$templateId.'}';
            $returnArray['RAWRESPONSE']=$template->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Updates a template, by ID.
     *
     * @param string  $templateId
     * @param Array $requestData
     * @return Array|Object
     */
    public function UpdateInvoiceTemplate($templateId,$requestData){
        try {
            $invoiceTemplateData = new TemplateData();
            /**
             * Setting Items in Invoice Template
             */
            if(isset($requestData['InvoiceItemArray'])){
                $itemArray = array();

                foreach ($requestData['InvoiceItemArray'] as $item) {
                    $InvoiceItem = new InvoiceItem();

                    if(isset($item['UnitPrice']) && count(array_filter($item['UnitPrice'])) > 0){
                        $ItemCurrency = new Currency();
                        $this->setArrayToMethods(array_filter($item['UnitPrice']), $ItemCurrency);
                        $InvoiceItem->setUnitPrice($ItemCurrency);
                    }
                    unset($item['UnitPrice']);

                    if(isset($item['Tax']) && count(array_filter($item['Tax'])) > 0){
                        $ItemTax = new Tax();
                        $this->setArrayToMethods(array_filter($item['Tax']), $ItemTax);
                        $InvoiceItem->setTax($ItemTax);
                    }
                    unset($item['Tax']);

                    if(isset($item['Discount']) && count(array_filter($item['Discount'])) > 0){
                        $ItemCost = new Cost();
                        $this->setArrayToMethods(array_filter($item['Discount']), $ItemCost);
                        $InvoiceItem->setDiscount($ItemCost);
                    }
                    unset($item['Discount']);

                    if(count(array_filter($item))>0){
                        $this->setArrayToMethods(array_filter($item), $InvoiceItem);
                        array_push($itemArray, $InvoiceItem);
                        $invoiceTemplateData->addItem($InvoiceItem);
                    }
                }
            }


            // ### Setting Merchant info to invoice template object.
            // ### Start
            if(isset($requestData['merchantInfo'])){
                $MerchantInfo = new MerchantInfo();
                if( isset($requestData['merchantInfo']) && count(array_filter($requestData['merchantInfo'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['merchantInfo']), $MerchantInfo);
                }
                $merchantPhone = new Phone();
                if( isset($requestData['merchantPhone']) && count(array_filter($requestData['merchantPhone'])) > 0 ){
                    $this->setArrayToMethods(array_filter($requestData['merchantPhone']), $merchantPhone);
                    $MerchantInfo->setPhone($merchantPhone);
                }
                $merchantAddress = new Address();
                if(isset($requestData['merchantAddress']) && count(array_filter($requestData['merchantAddress'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['merchantAddress']), $merchantAddress);
                    $MerchantInfo->setAddress($merchantAddress);
                }
                $invoiceTemplateData->setMerchantInfo($MerchantInfo);
            }

            // ### End

            // ### Setting Billing Info to invoice object.
            // ### Start

            if(isset($requestData['billingInfo'])){
                $BillingInfo = new BillingInfo();
                if( isset($requestData['billingInfo']) && count(array_filter($requestData['billingInfo'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['billingInfo']), $BillingInfo);
                }

                $InvoiceAddress = new InvoiceAddress();
                if(isset($requestData['billingInfoAddress']) && count(array_filter($requestData['billingInfoAddress'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['billingInfoAddress']), $InvoiceAddress);
                    $BillingInfo->setAddress($InvoiceAddress);
                }

                $billingPhone = new Phone();
                if(count(array_filter($requestData['billingInfoPhone'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['billingInfoPhone']), $billingPhone);
                    $BillingInfo->setPhone($billingPhone);
                }
                $invoiceTemplateData->setBillingInfo(array($BillingInfo));
            }
            //End


            // ### Shipping Information
            // ### Start

            if(isset($requestData['shippingInfo'])){
                $ShippingInfo = new ShippingInfo();
                $this->setArrayToMethods(array_filter($requestData['shippingInfo']), $ShippingInfo);

                $ShippingInfoPhone = new Phone();
                if(isset($requestData['shippingInfoPhone']) && count(array_filter($requestData['shippingInfoPhone'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['shippingInfoPhone']), $ShippingInfoPhone);
                    $ShippingInfo->setPhone($ShippingInfoPhone);
                }

                $ShippingInfoInvoiceAddress = new InvoiceAddress();
                if(isset($requestData['shippingInfoAddress']) && count(array_filter($requestData['shippingInfoAddress'])) > 0){
                    $this->setArrayToMethods(array_filter($requestData['shippingInfoAddress']), $ShippingInfoInvoiceAddress);
                    $ShippingInfo->setAddress($ShippingInfoInvoiceAddress);
                }
                $invoiceTemplateData->setShippingInfo($ShippingInfo);
            }
            if(isset($requestData['templateData'])){
                if(isset($requestData['templateData']['MinimumAmountDue']) && count(array_filter($requestData['templateData']['MinimumAmountDue'])) > 0){
                    $TemplateMinimumAmountDueCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($requestData['templateData']['MinimumAmountDue']), $TemplateMinimumAmountDueCurrency);
                    $invoiceTemplateData->setMinimumAmountDue($TemplateMinimumAmountDueCurrency);
                }
                unset($requestData['templateData']['MinimumAmountDue']);

                if(isset($requestData['templateData']['TotalAmount']) && count(array_filter($requestData['templateData']['TotalAmount'])) > 0){
                    $TemplateTotalAmountCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($requestData['templateData']['TotalAmount']), $TemplateTotalAmountCurrency);
                    $invoiceTemplateData->setTotalAmount($TemplateTotalAmountCurrency);
                }
                unset($requestData['templateData']['TotalAmount']);
                $this->setArrayToMethods(array_filter($requestData['templateData']), $invoiceTemplateData);
            }

            $templateDataCcInfo = isset($requestData['TemplateDataCcInfo']) ? trim($requestData['TemplateDataCcInfo']) : '';
            if(!empty($templateDataCcInfo)){
                $invoiceTemplateData->addCcInfo($templateDataCcInfo);
            }

            if(isset($requestData['templateDiscount']) && count(array_filter($requestData['templateDiscount'])) > 0){
                $templateDiscountCost = new Cost();
                $this->setArrayToMethods(array_filter($requestData['templateDiscount']), $templateDiscountCost);
                $invoiceTemplateData->setDiscount($templateDiscountCost);
            }

            if(isset($requestData['paymentTerm']) && count(array_filter($requestData['paymentTerm'])) > 0){
                $PaymentTerm = new PaymentTerm();
                $this->setArrayToMethods(array_filter($requestData['paymentTerm']), $PaymentTerm);
                $invoiceTemplateData->setPaymentTerm($PaymentTerm);
            }

            if(isset($requestData['attachments']) && count(array_filter($requestData['attachments'])) > 0){
                $attachment = new FileAttachment();
                $this->setArrayToMethods(array_filter($requestData['attachments']), $attachment);
                $invoiceTemplateData->setAttachments(array($attachment));
            }

            // ### Template Settings
            $displayPreferences = new TemplateSettingsMetadata();
            if(isset($requestData['TemplateSettingsMetadata']) && count(array_filter($requestData['TemplateSettingsMetadata']))>0){
                $this->setArrayToMethods(array_filter($requestData['TemplateSettingsMetadata']), $displayPreferences);
            }

            $settingDate = new TemplateSettings();
            if(isset($requestData['TemplateSettings']) && count(array_filter($requestData['TemplateSettings']))>0){
                $this->setArrayToMethods(array_filter($requestData['TemplateSettings']), $settingDate);
            }

            if(count(array_filter((array)$displayPreferences)) > 0){
                $settingDate->setDisplayPreference($displayPreferences);
            }
            // ### Template
            $invoiceTemplate = new Template();
            $invoiceTemplate->setTemplateId($templateId);
            if(isset($requestData['Template']) && count(array_filter($requestData['Template'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['Template']), $invoiceTemplate);
            }

            if(count(array_filter((array)$invoiceTemplateData)) > 0){
                $invoiceTemplate->setTemplateData($invoiceTemplateData);
            }

            if(count(array_filter((array)$settingDate)) > 0){
                $invoiceTemplate->addSetting($settingDate);
            }

            $requestArray = clone $invoiceTemplate;
            $invoiceTemplate->update($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICE_TEMPLATE'] = $invoiceTemplate->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$invoiceTemplate->toJSON();  
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

}

