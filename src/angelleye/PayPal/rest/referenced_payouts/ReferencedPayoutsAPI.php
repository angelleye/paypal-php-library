<?php

namespace angelleye\PayPal\rest\referenced_payouts;

use \angelleye\PayPal\ReferencedPayoutsClass;
use \angelleye\PayPal\RestClass;

class ReferencedPayoutsAPI extends RestClass {

    private $_api_context;

    public function __construct($configArray) {
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }

    public function create_referenced_batch_payout($parameters) {
         $referancePayoutObject = new ReferencedPayoutsClass();
        try {
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $payouts = $referancePayoutObject->referenced_payouts($params, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYOUTS'] = $payouts->toArray();
            $returnArray['RAWREQUEST'] = $requestArray;
            $returnArray['RAWRESPONSE'] = $payouts->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

}
