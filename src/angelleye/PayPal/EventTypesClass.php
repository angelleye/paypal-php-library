<?php

namespace angelleye\PayPal;

use PayPal\Common\PayPalModel;
use PayPal\Common\PayPalResourceModel;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use PayPal\Validation\ArgumentValidator;

class EventTypesClass extends PayPalResourceModel {

    public function get_all( $apiContext = null, $restCall = null) {
        
        $payLoad = "";
        $json = self::executeCall(
                "/v1/notifications/webhooks-event-types",
                "GET",
                $payLoad,
                null,
                $apiContext,
                $restCall
        );
        $ret = new EventTypesClass();
        return $ret->fromJson($json);
    }
    
     public function get_by_id($webhook_id,$apiContext = null, $restCall = null) {
        
        ArgumentValidator::validate($webhook_id, 'webhook_id');
         
        $payLoad = "";
        $json = self::executeCall(
                "/v1/notifications/webhooks/".$webhook_id."/event-types",
                "GET",
                $payLoad,
                null,
                $apiContext,
                $restCall
        );
        $ret = new EventTypesClass();
        return $ret->fromJson($json);
    }
    
    public function verify_webhook_signature_api($params,$apiContext = null, $restCall = null) {
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');        
        $payLoad = json_encode($params);      
        $json = self::executeCall(
            "/v1/notifications/verify-webhook-signature", 
            "GET", 
            $payLoad,
            null,
            $apiContext,
            $restCall
        );        
        $object = new EventTypesClass();
        return $object->fromJson($json);
    }
    
    public function simulate_webhook_event_api($params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');        
        $payLoad = json_encode($params);      
        $json = self::executeCall(
            "/v1/notifications/simulate-event", 
            "POST", 
            $payLoad,
            null,
            $apiContext,
            $restCall
        );        
        $object = new EventTypesClass();
        return $object->fromJson($json);
    }

}
