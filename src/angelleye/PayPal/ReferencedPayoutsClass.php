<?php

namespace angelleye\PayPal;

use PayPal\Common\PayPalModel;
use PayPal\Common\PayPalResourceModel;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use PayPal\Validation\ArgumentValidator;

class ReferencedPayoutsClass extends PayPalResourceModel {

    public function referenced_payouts($params, $apiContext = null, $restCall = null) {

         if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/payments/referenced-payouts",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );     
        $object = new ReferencedPayoutsClass();
        return $object->fromJson($json);
    }

}
