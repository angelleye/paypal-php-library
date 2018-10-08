<?php

namespace angelleye\PayPal\rest\customerdisputes;

use PayPal\Api\Plan;
use \angelleye\PayPal\CustomerDisputesClass;
use \angelleye\PayPal\RestClass;

class CustomerDisputesAPI extends RestClass {

    private $_api_context;

    public function __construct($configArray) {
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }

    public function list_all($parameters) {
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $disputes = CustomerDisputesClass::list_disputes($params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTES'] = $disputes->toArray();            
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$disputes->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }
    }
    
    public function showByID($dispute_id){
        $disputeObject = new CustomerDisputesClass();
        try {
            $dispute = $disputeObject->get($dispute_id,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']='{dispute_id:'.$dispute_id.'}';
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    public function accept_claim($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {
            $params = $this->checkEmptyObject($parameters);            
            $requestArray = json_encode($params);
            $dispute = $disputeObject->dispute_accept_claim($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    
    public function settle_dispute($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {
            $params = $this->checkEmptyObject($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->adjudicate($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    public function dispute_appeal($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {
            $params = array_map('array_filter', $parameters);
            $params = array_filter($params);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->appeal($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }  
    }
    
    public function disputes_escalate($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->escalate($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    public function disputes_make_offer($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->make_offer($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    public function disputes_provide_evidence($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->provide_evidence($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
}
