<?php

namespace angelleye\PayPal\rest\paypal_sync;


use \angelleye\PayPal\PayPalSyncClass;
use \angelleye\PayPal\RestClass;

class PayPalSyncAPI extends RestClass {

    private $_api_context;

    public function __construct($configArray) {
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }

    public function list_transactions($parameters) {
        try {
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $disputes = PayPalSyncClass::transactions($params, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTES'] = $disputes->toArray();
            $returnArray['RAWREQUEST'] = $requestArray;
            $returnArray['RAWRESPONSE'] = $disputes->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

}
