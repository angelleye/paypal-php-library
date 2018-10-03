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
            // need to add here some code for the json to array and need proper request and response too
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $t = $this->createErrorResponse($ex);
            echo "<pre>";
            var_dump($t);
            exit;
        }
    }

}
