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

}
