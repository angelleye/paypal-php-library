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
            $this->setArrayToMethods($requestData['batchHeader'], $senderBatchHeader);
            
            $senderItem = new PayoutItem();
            $this->setArrayToMethods($requestData['PayoutItem'], $senderItem);
            $senderItem->setAmount(new Currency(json_encode($requestData['amount'])));
            
            
            $payouts->setSenderBatchHeader($senderBatchHeader)
                    ->addItem($senderItem);

            $output = $payouts->createSynchronous($this->_api_context);
            return $output;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function create_batch_payout($requestData){
        
        try {
            $payouts = new Payout();
            $senderBatchHeader = new PayoutSenderBatchHeader();
            $this->setArrayToMethods($requestData['batchHeader'], $senderBatchHeader);
            
            foreach ($requestData['PayoutItem'] as $value) {
                $senderItem = new PayoutItem();
                $this->setArrayToMethods($value, $senderItem);
                $senderItem->setAmount(new Currency(json_encode($requestData['amount'])));
                $payouts->addItem($senderItem);
            }
            
            $payouts->setSenderBatchHeader($senderBatchHeader);
                    
            $output = $payouts->create(null,$this->_api_context);
            return $output;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function get_payout_batch_status($payoutBatchId){
        try {
            $output = Payout::get($payoutBatchId, $this->_api_context);
            return $output;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function get_payout_item_status($payoutItemId){
        try {
            $output = PayoutItem::get($payoutItemId, $this->_api_context);
            return $output;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function cancel_payout_item($payoutItemId){
        try {
            $PayoutItem = PayoutItem::get($payoutItemId, $this->_api_context);
            if($PayoutItem->transaction_status == 'UNCLAIMED'){
                $output = PayoutItem::cancel($payoutItemId, $this->_api_context);
                return $output;
            }
            else{
                return "Payout Item Status is not UNCLAIMED";
            }           
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
}

?>