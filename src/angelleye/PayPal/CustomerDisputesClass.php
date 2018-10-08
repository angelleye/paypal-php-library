<?php

namespace angelleye\PayPal;

use PayPal\Common\PayPalModel;
use PayPal\Common\PayPalResourceModel;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use PayPal\Validation\ArgumentValidator;

class CustomerDisputesClass extends PayPalResourceModel {
    
    public static function list_disputes($params,$apiContext = null, $restCall = null) {
        
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');        
        $payLoad = "";
        $allowedParams = array(
            'start_time' => 1,
            'disputed_transaction_id' => 1,
            'page_size' => 1,
            'next_page_token' => 1,
            'dispute_state' => 1            
        );
        $json = self::executeCall(
            "/v1/customer/disputes" . "?" . http_build_query(array_intersect_key($params, $allowedParams)), 
            "GET", 
            $payLoad,
            null,
            $apiContext,
            $restCall
        );        
        $object = new CustomerDisputesClass();        
        return $object->fromJson($json);
    }
    
    
    public function get($dispute_id, $apiContext = null, $restCall = null){
        
        ArgumentValidator::validate($dispute_id, 'dispute_id');
        $payLoad = "";
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id,
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json);
    }
    
    
    public function dispute_accept_claim($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/accept-claim",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json);
    }
    
    public function adjudicate($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/adjudicate",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json);    
    }
    
    public function appeal($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/appeal",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json);    
    }
    
    public function escalate($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/escalate",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json); 
    }
    
    public function make_offer($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/make-offer",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json); 
    }
    
}
