<?php

namespace angelleye\PayPal\rest\invoice;

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

class InvoiceAPI extends RestClass {

    private $_api_context;
    public function __construct($configArray) {        
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }
    
    public function create_invoice($requestData){
            try {
                    $invoice = new Invoice();
                    
                    // ### Setting Merchant info to invoice object.
                    // ### Start
                    $MerchantInfo = new MerchantInfo();
                    if( isset($requestData['merchantInfo'])){
                        $this->setArrayToMethods($this->checkEmptyObject($requestData['merchantInfo']), $MerchantInfo);
                    }
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

                    if(!empty($this->checkEmptyObject((array)$MerchantInfo))){
                        $invoice->setMerchantInfo($MerchantInfo);
                    }
                    // ### End
                    
                    // ### Setting cc_info
                    // ### Start
                    
                    $Participant = new Participant();
                    if(isset($requestData['ccInfo'])){
                       $this->setArrayToMethods($this->checkEmptyObject($requestData['ccInfo']), $Participant);
                    }
                    if (!empty($this->checkEmptyObject((array)$Participant))) {
                        $invoice->setCcInfo(array($Participant));
                    }
                    
                    // ### End
                    
                    // ### Setting Minimum Amount Due
                    // ### Start                            
                        $MinAmountCurrency = new Currency();
                        if(isset($requestData['MinimumAmountDue'])){
                            $this->setArrayToMethods($this->checkEmptyObject($requestData['MinimumAmountDue']), $MinAmountCurrency);
                        }
                        if (!empty($this->checkEmptyObject((array)$MinAmountCurrency))) {
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
                    if (!empty($this->checkEmptyObject((array)$BillingInfo))) {
                        $invoice->setBillingInfo(array($BillingInfo));                        
                    }   
                    //End
                    
                    // ### Add items in Invoice object.
                    // ### Start.    
                   
                    $itemArray = array();
                    foreach ($requestData['itemArray'] as $item) {
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
                        if( isset($item['Discount']) && count(array_filter($item['Discount'])) > 0){
                            $ItemCost = new Cost();
                            $this->setArrayToMethods(array_filter($item['Discount']), $ItemCost);
                            $InvoiceItem->setDiscount($ItemCost);
                        }
                        unset($item['Discount']);
                        
                        $this->setArrayToMethods(array_filter($item), $InvoiceItem);
                        array_push($itemArray, $InvoiceItem);
                    }
                    if (!empty($this->checkEmptyObject($itemArray))) {
                        $invoice->setItems($itemArray);    
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
                    if (!empty($this->checkEmptyObject((array)$ShippingInfo))) {
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
                        foreach ($requestData['attachments'] as $value) {
                            $attachment = new FileAttachment();
                            $attachment->setName($value['Name']);
                            $attachment->setUrl($value['url']);
                            $invoice->setAttachments(array($attachment));
                        }                        
                    }
                                                                                
                // ### Create Invoice
                // Create an invoice by calling the invoice->create() method
                $requestArray = clone $invoice;
                $invoice->create($this->_api_context);
                $returnArray['INVOICE']=$invoice->toArray();
                $returnArray['RESULT'] = 'Success';
                $returnArray['RAWREQUEST']=$requestArray->toJSON();
                $returnArray['RAWRESPONSE']=$invoice->toJSON();
                return $returnArray;
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
                return $this->createErrorResponse($ex);
            }        
    }        
    
    public function list_invoice($params){
        try {
            
            $invoices = Invoice::getAll(array_filter($params), $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICES'] = $invoices->toArray();
            return $returnArray;
            
        }  catch (\PayPal\Exception\PayPalConnectionException $ex) {
               return $this->createErrorResponse($ex);
        }
    }

    public function get_invoice($invoiceId){
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
    
    public function cancel_invoice($cancelNotification,$InvoiceID){
        try {
            $notify = new CancelNotification();
            $this->setArrayToMethods(array_filter($cancelNotification), $notify);
            
            $invoice = new Invoice();
            $invoice->setId($InvoiceID);
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
    
    public function record_refund($requestData){
        try {
            $invoice  = new Invoice();
            $invoice->setId($requestData['invoiceId']);
            
            $refund = new RefundDetail();
            $this->setArrayToMethods($requestData['refundDetail'], $refund);
            
            if(count(array_filter($requestData['amount'])) > 0){
                $amt= new Currency(json_encode($requestData['amount']));
                $refund->setAmount($amt);
            }
            $requestArray = clone $invoice;
            $refundStatus = $invoice->recordRefund($refund, $this->_api_context);
            $invoice = Invoice::get($requestData['invoiceId'], $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICE'] = $invoice->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$invoice->toJSON();
            return $returnArray;                                                
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    public function remind_invoice($remindNotification,$InvoiceID){
        try {
            $invoice  = new Invoice();
            $notify = new Notification();
            $this->setArrayToMethods(array_filter($remindNotification), $notify);
            $remindStatus = $invoice->remind($notify, $this->_api_context);
            $requestArray = clone $invoice;
            $invoice = Invoice::get($InvoiceID, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['REMIND_INVOICE'] = $invoice->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$invoice->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    public function retrieve_QR_code($parameters,$InvoiceID,$path){
        
        try{
            $image = Invoice::qrCode($InvoiceID, array_filter($parameters), $this->_api_context);
            $path = $image->saveToFile($path);
            $returnArray['RESULT'] = 'Success';
            return array('Image' => $image->getImage());
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }                
    }
    
    public function search_invoices($parameters){
        
        try{ 
            $search = new Search(json_encode(array_filter($parameters)));            
            $invoices = Invoice::search($search, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICES'] = $invoices->toArray();
            $returnArray['RAWREQUEST']=json_encode(array_filter($parameters));
            $returnArray['RAWRESPONSE']=$invoices->toJSON();
            return $returnArray;
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    public function update_invoice($requestData){
        
        try {       
            
            $invoice = $this->get_invoice($requestData['InvoiceID']);            
            
            // ### Setting Merchant info to invoice object.
            // ### Start
            $MerchantInfo = new MerchantInfo();
            $this->setArrayToMethods(array_filter($requestData['merchantInfo']), $MerchantInfo);

            $merchantPhone = new Phone();
            $this->setArrayToMethods(array_filter($requestData['merchantPhone']), $merchantPhone);
            $MerchantInfo->setPhone($merchantPhone);

            $merchantAddress = new Address();
            $this->setArrayToMethods(array_filter($requestData['merchantAddress']), $merchantAddress);
            $MerchantInfo->setAddress($merchantAddress);

            $invoice->setMerchantInfo($MerchantInfo);
            // ### End

            // ### Setting Billing Info to invoice object. 
            // ### Start

            $BillingInfo = new BillingInfo();
            $this->setArrayToMethods(array_filter($requestData['billingInfo']), $BillingInfo);

            $InvoiceAddress = new InvoiceAddress();
            $this->setArrayToMethods(array_filter($requestData['billingInfoAddress']), $InvoiceAddress);
            $BillingInfo->setAddress($InvoiceAddress);

            $billingPhone = new Phone();
            $this->setArrayToMethods(array_filter($requestData['billingInfoPhone']), $billingPhone);
            $BillingInfo->setPhone($billingPhone);

            $invoice->setBillingInfo(array($BillingInfo));


            //End

            // ### Add items in Invoice object.
            // ### Start.    

            if(count($requestData['itemArray'])>0){
            $itemArray = array();
            foreach ($requestData['itemArray'] as $item) {
                $InvoiceItem = new InvoiceItem();

                if(count(array_filter($item['UnitPrice'])) > 0){
                    $ItemCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($item['UnitPrice']), $ItemCurrency);
                    $InvoiceItem->setUnitPrice($ItemCurrency);
                }
                unset($item['UnitPrice']);
                if(count(array_filter($item['Tax'])) > 0){
                    $ItemTax = new Tax();
                    $this->setArrayToMethods(array_filter($item['Tax']), $ItemTax);
                    $InvoiceItem->setTax($ItemTax);
                }
                unset($item['Tax']);
                if(count(array_filter($item['Discount'])) > 0){
                    $ItemCost = new Cost();
                    $this->setArrayToMethods(array_filter($item['Discount']), $ItemCost);
                    $InvoiceItem->setDiscount($ItemCost);
                }
                unset($item['Discount']);

                $this->setArrayToMethods(array_filter($item), $InvoiceItem);
                array_push($itemArray, $InvoiceItem);
            }

            $invoice->setItems($itemArray);
            }
            // ### END

            // #### Final Discount
            // You can add final discount to the invoice as shown below. You could either use "percent" or "value" when providing the discount

            if(count(array_filter($requestData['finalDiscountForInvoice'])) > 0){
                $FinalDiscountCost = new Cost();
                $FinalDiscountCost->setPercent($requestData['finalDiscountForInvoice']['Percent']);
                $invoice->setDiscount($FinalDiscountCost);   
            }

            if(count(array_filter($requestData['paymentTerm'])) > 0){
                $PaymentTerm = new PaymentTerm();
                $this->setArrayToMethods(array_filter($requestData['paymentTerm']), $PaymentTerm);
                $invoice->setPaymentTerm($PaymentTerm);
            }

            // ### Shipping Information
            // ### Start

            $ShippingInfo = new ShippingInfo();
            $this->setArrayToMethods(array_filter($requestData['shippingInfo']), $ShippingInfo);

            $ShippingInfoPhone = new Phone();
            $this->setArrayToMethods(array_filter($requestData['shippingInfoPhone']), $ShippingInfoPhone);
            $ShippingInfo->setPhone($ShippingInfoPhone);

            $ShippingInfoInvoiceAddress = new InvoiceAddress();
            $this->setArrayToMethods(array_filter($requestData['shippingInfoAddress']), $ShippingInfoInvoiceAddress);
            $ShippingInfo->setAddress($ShippingInfoInvoiceAddress);

            $invoice->setShippingInfo($ShippingInfo);

            $this->setArrayToMethods(array_filter($requestData['invoiceData']), $invoice);
            
            if(count(array_filter($requestData['attachments'])) > 0){
                $attachment = new FileAttachment();
                $this->setArrayToMethods(array_filter($requestData['attachments']), $attachment);
                $invoice->setAttachments(array($attachment));
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

    public function send_invoice($invoiceId){
        try {
            $invoice = new Invoice();
            $invoice->setId($invoiceId);
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

    public function delete_invoice($invoiceId){
        
        try{
            $invoice = new Invoice();
            $invoice->setId($invoiceId);
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

    public function get_next_invoice_number(){
        try {
            $number = Invoice::generateNumber($this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['INVOICE_NUMBER'] = $number->toArray();
            $returnArray['RAWREQUEST']='';
            $returnArray['RAWRESPONSE']=$number->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }
    }
    
    public function record_payment($invoiceId,$record,$amount){
        try{
            $invoice = new Invoice();
            $invoice->setId($invoiceId);
            
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
    
    
    public function create_invoice_template($requestData){
        
        try {
            $invoiceTemplateData = new TemplateData();    
            $itemArray = array();
            
            foreach ($requestData['InvoiceItemArray'] as $item) {
                $InvoiceItem = new InvoiceItem();

                if(count(array_filter($item['UnitPrice'])) > 0){
                    $ItemCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($item['UnitPrice']), $ItemCurrency);
                    $InvoiceItem->setUnitPrice($ItemCurrency);
                }
                unset($item['UnitPrice']);
                if(count(array_filter($item['Tax'])) > 0){
                    $ItemTax = new Tax();
                    $this->setArrayToMethods(array_filter($item['Tax']), $ItemTax);
                    $InvoiceItem->setTax($ItemTax);
                }
                unset($item['Tax']);
                if(count(array_filter($item['Discount'])) > 0){
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

            // ### Setting Merchant info to invoice object.
            // ### Start
            $MerchantInfo = new MerchantInfo();
            if(count(array_filter($requestData['merchantInfo'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['merchantInfo']), $MerchantInfo);
            }
            $merchantPhone = new Phone();
            if(count(array_filter($requestData['merchantPhone'])) > 0 ){
                $this->setArrayToMethods(array_filter($requestData['merchantPhone']), $merchantPhone);
                $MerchantInfo->setPhone($merchantPhone);
            }
            $merchantAddress = new Address();
            if(count(array_filter($requestData['merchantAddress'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['merchantAddress']), $merchantAddress);
                $MerchantInfo->setAddress($merchantAddress);            
            }  
            
            if(count(array_filter((array)$MerchantInfo)) > 0){
                $invoiceTemplateData->setMerchantInfo($MerchantInfo);                        
            }
            
            // ### End

            // ### Setting Billing Info to invoice object. 
            // ### Start

            $BillingInfo = new BillingInfo();
            if(count(array_filter($requestData['billingInfo'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['billingInfo']), $BillingInfo);
            }
            
            $InvoiceAddress = new InvoiceAddress();
            if(count(array_filter($requestData['billingInfoAddress'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['billingInfoAddress']), $InvoiceAddress);
                $BillingInfo->setAddress($InvoiceAddress);
            }

            $billingPhone = new Phone();
            if(count(array_filter($requestData['billingInfoPhone'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['billingInfoPhone']), $billingPhone);
                $BillingInfo->setPhone($billingPhone);
            }
            if(count(array_filter((array)$BillingInfo)) > 0){
                $invoiceTemplateData->setBillingInfo(array($BillingInfo));
            }            
            
            //End


            // ### Shipping Information
            // ### Start

            $ShippingInfo = new ShippingInfo();
            if(count(array_filter($requestData['shippingInfo'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['shippingInfo']), $ShippingInfo);
            }
            
            $ShippingInfoPhone = new Phone();
            if(count(array_filter($requestData['shippingInfoPhone'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['shippingInfoPhone']), $ShippingInfoPhone);
                $ShippingInfo->setPhone($ShippingInfoPhone);                
            }
                       
            $ShippingInfoInvoiceAddress = new InvoiceAddress();
            if(count(array_filter($requestData['shippingInfoAddress'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['shippingInfoAddress']), $ShippingInfoInvoiceAddress);
                $ShippingInfo->setAddress($ShippingInfoInvoiceAddress);
            }
            if(count(array_filter((array)$ShippingInfo)) > 0){
                $invoiceTemplateData->setShippingInfo($ShippingInfo);
            }                        

            if(count(array_filter($requestData['templateData']['MinimumAmountDue'])) > 0){
                    $TemplateMinimumAmountDueCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($requestData['templateData']['MinimumAmountDue']), $TemplateMinimumAmountDueCurrency);
                    $invoiceTemplateData->setMinimumAmountDue($TemplateMinimumAmountDueCurrency);
            }
            unset($requestData['templateData']['MinimumAmountDue']);

            if(count(array_filter($requestData['templateData']['TotalAmount'])) > 0){
                    $TemplateTotalAmountCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($requestData['templateData']['TotalAmount']), $TemplateTotalAmountCurrency);
                    $invoiceTemplateData->setTotalAmount($TemplateTotalAmountCurrency);
            }
            unset($requestData['templateData']['TotalAmount']);

            if(!empty(trim($requestData['TemplateDataCcInfo']))){
                $invoiceTemplateData->addCcInfo($requestData['TemplateDataCcInfo']);
            }
            $this->setArrayToMethods(array_filter($requestData['templateData']), $invoiceTemplateData);

            if(count(array_filter($requestData['templateDiscount'])) > 0){
                $templateDiscountCost = new Cost();
                $this->setArrayToMethods(array_filter($requestData['templateDiscount']), $templateDiscountCost);
                $invoiceTemplateData->setDiscount($templateDiscountCost);
            }

            if(count(array_filter($requestData['paymentTerm'])) > 0){
                $PaymentTerm = new PaymentTerm();
                $this->setArrayToMethods(array_filter($requestData['paymentTerm']), $PaymentTerm);
                $invoiceTemplateData->setPaymentTerm($PaymentTerm);
            }

            if(count(array_filter($requestData['attachments'])) > 0){
                $attachment = new FileAttachment();
                $this->setArrayToMethods(array_filter($requestData['attachments']), $attachment);
                $invoiceTemplateData->setAttachments(array($attachment));
            }

             // ### Template Settings    
            $displayPreferences = new TemplateSettingsMetadata();
            if(count(array_filter($requestData['TemplateSettingsMetadata']))>0){
                $this->setArrayToMethods(array_filter($requestData['TemplateSettingsMetadata']), $displayPreferences);
            }
            
            $settingDate = new TemplateSettings();
            if(count(array_filter($requestData['TemplateSettings']))>0){
                $this->setArrayToMethods(array_filter($requestData['TemplateSettings']), $settingDate);
            }
            
            if(count(array_filter((array)$displayPreferences)) > 0){
                $settingDate->setDisplayPreference($displayPreferences);
            }
            
            // ### Template
            $invoiceTemplate = new Template();
            if(count(array_filter($requestData['Template'])) > 0){
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
    
    public function delete_invoice_template($template_id){
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
    
    public function get_all_invoice_templates($fields){
        
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
    
    public function get_invoice_template($templateId){
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

    public function update_invoice_template($templateId,$requestData){
        try {
            $invoiceTemplateData = new TemplateData();    
            $itemArray = array();
            
            foreach ($requestData['InvoiceItemArray'] as $item) {
                $InvoiceItem = new InvoiceItem();

                if(count(array_filter($item['UnitPrice'])) > 0){
                    $ItemCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($item['UnitPrice']), $ItemCurrency);
                    $InvoiceItem->setUnitPrice($ItemCurrency);
                }
                unset($item['UnitPrice']);
                if(count(array_filter($item['Tax'])) > 0){
                    $ItemTax = new Tax();
                    $this->setArrayToMethods(array_filter($item['Tax']), $ItemTax);
                    $InvoiceItem->setTax($ItemTax);
                }
                unset($item['Tax']);
                if(count(array_filter($item['Discount'])) > 0){
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

            // ### Setting Merchant info to invoice object.
            // ### Start
            $MerchantInfo = new MerchantInfo();
            if(count(array_filter($requestData['merchantInfo'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['merchantInfo']), $MerchantInfo);
            }
            $merchantPhone = new Phone();
            if(count(array_filter($requestData['merchantPhone'])) > 0 ){
                $this->setArrayToMethods(array_filter($requestData['merchantPhone']), $merchantPhone);
                $MerchantInfo->setPhone($merchantPhone);
            }
            $merchantAddress = new Address();
            if(count(array_filter($requestData['merchantAddress'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['merchantAddress']), $merchantAddress);
                $MerchantInfo->setAddress($merchantAddress);            
            }  
            
            if(count(array_filter((array)$MerchantInfo)) > 0){
                $invoiceTemplateData->setMerchantInfo($MerchantInfo);                        
            }
            
            // ### End

            // ### Setting Billing Info to invoice object. 
            // ### Start

            $BillingInfo = new BillingInfo();
            if(count(array_filter($requestData['billingInfo'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['billingInfo']), $BillingInfo);
            }
            
            $InvoiceAddress = new InvoiceAddress();
            if(count(array_filter($requestData['billingInfoAddress'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['billingInfoAddress']), $InvoiceAddress);
                $BillingInfo->setAddress($InvoiceAddress);
            }

            $billingPhone = new Phone();
            if(count(array_filter($requestData['billingInfoPhone'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['billingInfoPhone']), $billingPhone);
                $BillingInfo->setPhone($billingPhone);
            }
            if(count(array_filter((array)$BillingInfo)) > 0){
                $invoiceTemplateData->setBillingInfo(array($BillingInfo));
            }            
            
            //End


            // ### Shipping Information
            // ### Start

            $ShippingInfo = new ShippingInfo();
            if(count(array_filter($requestData['shippingInfo'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['shippingInfo']), $ShippingInfo);
            }
            
            $ShippingInfoPhone = new Phone();
            if(count(array_filter($requestData['shippingInfoPhone'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['shippingInfoPhone']), $ShippingInfoPhone);
                $ShippingInfo->setPhone($ShippingInfoPhone);                
            }
                       
            $ShippingInfoInvoiceAddress = new InvoiceAddress();
            if(count(array_filter($requestData['shippingInfoAddress'])) > 0){
                $this->setArrayToMethods(array_filter($requestData['shippingInfoAddress']), $ShippingInfoInvoiceAddress);
                $ShippingInfo->setAddress($ShippingInfoInvoiceAddress);
            }
            if(count(array_filter((array)$ShippingInfo)) > 0){
                $invoiceTemplateData->setShippingInfo($ShippingInfo);
            }                        

            if(count(array_filter($requestData['templateData']['MinimumAmountDue'])) > 0){
                    $TemplateMinimumAmountDueCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($requestData['templateData']['MinimumAmountDue']), $TemplateMinimumAmountDueCurrency);
                    $invoiceTemplateData->setMinimumAmountDue($TemplateMinimumAmountDueCurrency);
            }
            unset($requestData['templateData']['MinimumAmountDue']);

            if(count(array_filter($requestData['templateData']['TotalAmount'])) > 0){
                    $TemplateTotalAmountCurrency = new Currency();
                    $this->setArrayToMethods(array_filter($requestData['templateData']['TotalAmount']), $TemplateTotalAmountCurrency);
                    $invoiceTemplateData->setTotalAmount($TemplateTotalAmountCurrency);
            }
            unset($requestData['templateData']['TotalAmount']);

            if(!empty(trim($requestData['TemplateDataCcInfo']))){
                $invoiceTemplateData->addCcInfo($requestData['TemplateDataCcInfo']);
            }
            $this->setArrayToMethods(array_filter($requestData['templateData']), $invoiceTemplateData);

            if(count(array_filter($requestData['templateDiscount'])) > 0){
                $templateDiscountCost = new Cost();
                $this->setArrayToMethods(array_filter($requestData['templateDiscount']), $templateDiscountCost);
                $invoiceTemplateData->setDiscount($templateDiscountCost);
            }

            if(count(array_filter($requestData['paymentTerm'])) > 0){
                $PaymentTerm = new PaymentTerm();
                $this->setArrayToMethods(array_filter($requestData['paymentTerm']), $PaymentTerm);
                $invoiceTemplateData->setPaymentTerm($PaymentTerm);
            }

            if(count(array_filter($requestData['attachments'])) > 0){
                $attachment = new FileAttachment();
                $this->setArrayToMethods(array_filter($requestData['attachments']), $attachment);
                $invoiceTemplateData->setAttachments(array($attachment));
            }

             // ### Template Settings    
            $displayPreferences = new TemplateSettingsMetadata();
            if(count(array_filter($requestData['TemplateSettingsMetadata']))>0){
                $this->setArrayToMethods(array_filter($requestData['TemplateSettingsMetadata']), $displayPreferences);
            }
            
            $settingDate = new TemplateSettings();
            if(count(array_filter($requestData['TemplateSettings']))>0){
                $this->setArrayToMethods(array_filter($requestData['TemplateSettings']), $settingDate);
            }
            
            if(count(array_filter((array)$displayPreferences)) > 0){
                $settingDate->setDisplayPreference($displayPreferences);
            }
            
            

            // ### Template
            $invoiceTemplate = $this->get_invoice_template($templateId);
            if(count(array_filter($requestData['Template'])) > 0){
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

