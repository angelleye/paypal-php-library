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

}
