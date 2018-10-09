<?php
namespace angelleye\PayPal;


use PayPal\Common\PayPalModel;
use PayPal\Common\PayPalResourceModel;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use PayPal\Validation\ArgumentValidator;


class PayPalSyncClass extends PayPalResourceModel {
    
    public static function transactions($params,$apiContext = null, $restCall = null) {
        
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');        
        $payLoad = "";
        $allowedParams = array(
            'transaction_id' => 1,
            'transaction_type' => 1,
            'transaction_status' => 1,
            'transaction_amount' => 1,
            'transaction_currency' => 1,
            'start_date' => 1,
            'end_date' => 1,
            'payment_instrument_type' => 1,
            'store_id' => 1,
            'terminal_id' => 1,
            'fields' => 1,
            'balance_affecting_records_only' => 1,
            'page_size' => 1,
            'page' => 1
        );
        $json = self::executeCall(
            "/v1/reporting/transactions" . "?" . http_build_query(array_intersect_key($params, $allowedParams)), 
            "GET", 
            $payLoad,
            null,
            $apiContext,
            $restCall
        );        
        $object = new PayPalSyncClass();        
        return $object->fromJson($json);
    }
            
    
}