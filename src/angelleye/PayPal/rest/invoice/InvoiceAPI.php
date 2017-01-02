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
use PayPal\Api\RefundDetail;
use PayPal\Api\Search;
use PayPal\Api\ShippingInfo;
use PayPal\Api\Tax;
use PayPal\Api\Template;
use PayPal\Api\Templates;
use PayPal\Api\TemplateData;
use PayPal\Api\TemplateSettings;
use PayPal\Api\TemplateSettingsMetadata;

class InvoiceAPI {

    private $_api_context;

    public function __construct($configArray) {
        // setup PayPal api context 
        $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'], $configArray['ClientSecret'])
        );
    }    
    
    public function create_invoice($requestData){
            try {
                    $invoice = new Invoice();
                    
                    // ### Setting Merchant info to invoice object.
                    // ### Start
                    $MerchantInfo = new MerchantInfo();
                    if($this->checkEmptyObject($requestData['merchantInfo'])){
                        $this->setArrayToMethods(array_filter($requestData['merchantInfo']), $MerchantInfo);    
                    }
                    if($this->checkEmptyObject($requestData['merchantPhone'])){
                        $merchantPhone = new Phone();
                        $this->setArrayToMethods(array_filter($requestData['merchantPhone']), $merchantPhone);
                        $MerchantInfo->setPhone($merchantPhone);
                    }
                    if($this->checkEmptyObject($requestData['merchantAddress'])){
                        $merchantAddress = new Address();
                        $this->setArrayToMethods(array_filter($requestData['merchantAddress']), $merchantAddress);
                        $MerchantInfo->setAddress($merchantAddress);                        
                    }

                    if($this->checkEmptyObject((array)$MerchantInfo)){
                        $invoice->setMerchantInfo($MerchantInfo);
                    }
                    // ### End
                    
                    // ### Setting Billing Info to invoice object. 
                    // ### Start
                    $BillingInfo = new BillingInfo();
                    if($this->checkEmptyObject($requestData['billingInfo'])){
                        $this->setArrayToMethods(array_filter($requestData['billingInfo']), $BillingInfo);
                    }
                    if ($this->checkEmptyObject($requestData['billingInfoAddress'])) {
                        $InvoiceAddress = new InvoiceAddress();
                        $this->setArrayToMethods(array_filter($requestData['billingInfoAddress']), $InvoiceAddress);
                        $BillingInfo->setAddress($InvoiceAddress);
                    }
                    if ($this->checkEmptyObject($requestData['billingInfoPhone'])) {
                        $billingPhone = new Phone();
                        $this->setArrayToMethods(array_filter($requestData['billingInfoPhone']), $billingPhone);
                        $BillingInfo->setPhone($billingPhone);
                    }
                    if ($this->checkEmptyObject((array)$BillingInfo)) {
                        $invoice->setBillingInfo(array($BillingInfo));                        
                    }   
                    //End
                    
                    // ### Add items in Invoice object.
                    // ### Start.    
                   
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
                    if ($this->checkEmptyObject($itemArray)) {
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
                    if ($this->checkEmptyObject($requestData['shippingInfo'])) {
                        $this->setArrayToMethods(array_filter($requestData['shippingInfo']), $ShippingInfo);
                    }
                    if ($this->checkEmptyObject($requestData['shippingInfoPhone'])) {
                        $ShippingInfoPhone = new Phone();
                        $this->setArrayToMethods(array_filter($requestData['shippingInfoPhone']), $ShippingInfoPhone);
                        $ShippingInfo->setPhone($ShippingInfoPhone);
                    }                    
                    if ($this->checkEmptyObject($requestData['shippingInfoAddress'])) {
                        $ShippingInfoInvoiceAddress = new InvoiceAddress();
                        $this->setArrayToMethods(array_filter($requestData['shippingInfoAddress']), $ShippingInfoInvoiceAddress);
                        $ShippingInfo->setAddress($ShippingInfoInvoiceAddress);
                    }
                    if ($this->checkEmptyObject((array)$ShippingInfo)) {
                        $invoice->setShippingInfo($ShippingInfo);
                    }
                    if ($this->checkEmptyObject($requestData['invoiceData'])) {
                        $this->setArrayToMethods(array_filter($requestData['invoiceData']), $invoice);
                    }
                    
                // ### Create Invoice
                // Create an invoice by calling the invoice->create() method
                
                $invoice->create($this->_api_context);
                
                return $invoice;
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
               return $ex->getData();
            }        
    }
    
    public function list_invoice($params){
        try {
            
            $invoices = Invoice::getAll(array_filter($params), $this->_api_context);
            return $invoices;
            
        }  catch (\PayPal\Exception\PayPalConnectionException $ex) {
               return $ex->getData();
        }
    }

    public function get_invoice($invoiceId){
        try {
            $invoice = Invoice::get($invoiceId, $this->_api_context);
            return $invoice;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function cancel_invoice($cancelNotification,$InvoiceID){
        try {
            $notify = new CancelNotification();
            $this->setArrayToMethods(array_filter($cancelNotification), $notify);
            
            $invoice = new Invoice();
            $invoice->setId($InvoiceID);
            
            $cancelStatus = $invoice->cancel($notify, $this->_api_context);
            return $cancelStatus;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            
            $refundStatus = $invoice->recordRefund($refund, $this->_api_context);
            $invoice = Invoice::get($requestData['invoiceId'], $this->_api_context);
            
            return array('Refund Status' => $refundStatus ,'Invoice' => $invoice );
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function remind_invoice($remindNotification,$InvoiceID){
        try {
            
            $notify = new Notification();
            $this->setArrayToMethods(array_filter($remindNotification), $notify);
            $remindStatus = $invoice->remind($notify, $this->_api_context);
            $invoice = Invoice::get($InvoiceID, $this->_api_context);
            return array('RemindStatus' => '$remindStatus' , 'Invoice' => $invoice);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }        
    }
    
    public function retrieve_QR_code($parameters,$InvoiceID,$path){
        
        try{
            $image = Invoice::qrCode($InvoiceID, array_filter($parameters), $this->_api_context);
            $path = $image->saveToFile($path);
            return array('Image' => $image->getImage());
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }                
    }
    
    public function search_invoices($parameters){
        
        try{ 
            $search = new Search(json_encode(array_filter($parameters)));
            
            $invoices = Invoice::search($search, $this->_api_context);
            return $invoices;
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            
            $invoice->update($this->_api_context);
            $invoice = Invoice::get($invoice->getId(), $this->_api_context);
            return $invoice;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
          return $ex->getData();
        }
    }

    public function send_invoice($invoiceId){
        try {
            $invoice = new Invoice();
            $invoice->setId($invoiceId);
            $sendStatus = $invoice->send($this->_api_context);
            $Getinvoice = Invoice::get($invoice->getId(), $this->_api_context);
            return array('SendStatus' => $sendStatus , 'Invoice' => $Getinvoice);            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function delete_invoice($invoiceId){
        
        try{
            $invoice = new Invoice();
            $invoice->setId($invoiceId);
            $deleteStatus = $invoice->delete($this->_api_context);
            return $deleteStatus;
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function get_next_invoice_number(){
        try {
            $number = Invoice::generateNumber($this->_api_context);
            return $number;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $ex->getData();
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
            return array('Record Status' => $recordStatus,'Invoice' => $returnInvoice);
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            
            $invoiceTemplate->create($this->_api_context);
            return $invoiceTemplate;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function delete_invoice_template($template_id){
        try {
            $template = new Template();
            $template->setTemplateId($template_id);
            $deleteStatus = $template->delete($this->_api_context);
            return $deleteStatus;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function get_all_invoice_templates($fields){
        
        try {
            $templates = Templates::getAll($fields, $this->_api_context);
            return $templates;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function get_invoice_template($templateId){
        try {
            $template = Template::get($templateId, $this->_api_context);
            return $template;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            
            $invoiceTemplate->update($this->_api_context);
            return $invoiceTemplate;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function setArrayToMethods($array, $object) {
        foreach ($array as $key => $val) {
            $method = 'set' . $key;
            if (!empty($val)) {
                if (method_exists($object, $method)) {
                    $object->$method($val);
                }
            }
        }
        return TRUE;
    }    
    
    public function checkEmptyObject($array){
        if(count(array_filter($array)) > 0){
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
}

?>
