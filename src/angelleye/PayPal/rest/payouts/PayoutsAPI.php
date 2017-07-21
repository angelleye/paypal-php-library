<?php

namespace angelleye\PayPal\rest\payouts;

use PayPal\Api\Currency;
use PayPal\Api\Payout;
use PayPal\Api\PayoutItem;
use PayPal\Api\PayoutSenderBatchHeader;

class PayoutsAPI {

    private $_api_context;

    public function __construct($configArray) {
        // setup PayPal api context 
        $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'], $configArray['ClientSecret'])
        );
    }

    public function create_single_payout($requestData) {        
        
        try {
            $payouts = new Payout();
            $senderBatchHeader = new PayoutSenderBatchHeader();
            if ($this->checkEmptyObject($requestData['batchHeader'])) {
                $this->setArrayToMethods(array_filter($requestData['batchHeader']), $senderBatchHeader);
            }
            $senderItem = new PayoutItem();
            if ($this->checkEmptyObject($requestData['PayoutItem'])) {
                $this->setArrayToMethods($requestData['PayoutItem'], $senderItem);
            }
            $senderItem->setAmount(new Currency(json_encode($requestData['amount'])));
            
            if ($this->checkEmptyObject((array)$senderBatchHeader)) {
                $payouts->setSenderBatchHeader($senderBatchHeader);    
            }
            if ($this->checkEmptyObject((array)$senderItem)) {
                $payouts->addItem($senderItem);
            }   
            $requestArray = clone $payouts;
            $output = $payouts->createSynchronous($this->_api_context);
            $returnArray=$output->toArray();
            $returnArray['REQUESTDATA']=$requestArray->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $errorReturnArray['ERRORS']=  json_decode($ex->getData());
            $errorReturnArray['REQUESTDATA']=$requestArray->toArray();
            $errorReturnArray['RAWREQUEST']=$requestArray->toJSON();
            $errorReturnArray['RAWRESPONSE']='';
            return $errorReturnArray;
        }
    }
    
    public function create_batch_payout($requestData){
        
        try {
            $payouts = new Payout();
            $senderBatchHeader = new PayoutSenderBatchHeader();
            $this->setArrayToMethods(array_filter($requestData['batchHeader']), $senderBatchHeader);
            
            foreach ($requestData['PayoutItem'] as $value) {
                $senderItem = new PayoutItem();
                $this->setArrayToMethods(array_filter($value), $senderItem);
                $senderItem->setAmount(new Currency(json_encode($requestData['amount'])));
                $payouts->addItem($senderItem);
            }
            if ($this->checkEmptyObject((array)$senderBatchHeader)) {
                $payouts->setSenderBatchHeader($senderBatchHeader);
            }
                                
           $requestArray = clone $payouts; 
           $output = $payouts->create(null,$this->_api_context);
           $returnArray=$output->toArray();
           $returnArray['REQUESTDATA']=$requestArray->toArray();
           $returnArray['RAWREQUEST']=$requestArray->toJSON();
           $returnArray['RAWRESPONSE']=$output->toJSON();            
           return $returnArray;                      
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $errorReturnArray['ERRORS']=  json_decode($ex->getData());
            $errorReturnArray['REQUESTDATA']=$requestArray->toArray();
            $errorReturnArray['RAWREQUEST']=$requestArray->toJSON();
            $errorReturnArray['RAWRESPONSE']='';
            return $errorReturnArray;
        }
    }

    public function get_payout_batch_status($payoutBatchId){
        try {
            $output = Payout::get($payoutBatchId, $this->_api_context);
            $returnArray=$output->toArray();
            $returnArray['REQUESTDATA'] =array('id' => $payoutBatchId);
            $returnArray['RAWREQUEST']='{id:'.$payoutBatchId.'}';
            $returnArray['RAWRESPONSE']=$output->toJSON();            
           return $returnArray;              
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $errorReturnArray['ERRORS']=  json_decode($ex->getData());
            $errorReturnArray['REQUESTDATA']=array('id' => $payoutBatchId);
            $errorReturnArray['RAWREQUEST']='{id:'.$payoutBatchId.'}';
            $errorReturnArray['RAWRESPONSE']='';
            return $errorReturnArray;
        }
    }
    
    public function get_payout_item_status($payoutItemId){
        try {
            $output = PayoutItem::get($payoutItemId, $this->_api_context);
            $returnArray=$output->toArray();
            $returnArray['REQUESTDATA'] =array('id' => $payoutItemId);
            $returnArray['RAWREQUEST']='{id:'.$payoutItemId.'}';
            $returnArray['RAWRESPONSE']=$output->toJSON();     
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $errorReturnArray['ERRORS']=  json_decode($ex->getData());
            $errorReturnArray['REQUESTDATA']=array('id' => $payoutItemId);
            $errorReturnArray['RAWREQUEST']='{id:'.$payoutItemId.'}';
            $errorReturnArray['RAWRESPONSE']='';
            return $errorReturnArray;
        }
    }

    public function cancel_payout_item($payoutItemId){
        try {
            $PayoutItem = PayoutItem::get($payoutItemId, $this->_api_context);
            if($PayoutItem->transaction_status == 'UNCLAIMED'){
                 $output = PayoutItem::cancel($payoutItemId, $this->_api_context);
                 $returnArray=$output->toArray();
                 $returnArray['REQUESTDATA'] =array('id' => $payoutItemId);
                 $returnArray['RAWREQUEST']='{id:'.$payoutItemId.'}';
                 $returnArray['RAWRESPONSE']=$output->toJSON();
                 return $returnArray;                                      
            }
            else{
                return "Payout Item Status is not UNCLAIMED";
            }           
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $errorReturnArray['ERRORS']=  json_decode($ex->getData());
            $errorReturnArray['REQUESTDATA']=array('id' => $payoutItemId);
            $errorReturnArray['RAWREQUEST']='{id:'.$payoutItemId.'}';
            $errorReturnArray['RAWRESPONSE']='';
            return $errorReturnArray;
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
