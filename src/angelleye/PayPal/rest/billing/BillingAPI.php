<?php

namespace angelleye\PayPal\rest\billing;

use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Common\PayPalModel;

class BillingAPI {

    private $_api_context;

    public function __construct($configArray) {
        // setup PayPal api context 
        $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'], $configArray['ClientSecret'])
        );
    }
    
    public function create_plan($requestData){
        
        // ### Create Plan
        try {
            // Create a new instance of Plan object
            $plan = new Plan();
            $this->setArrayToMethods(array_filter($requestData['plan']), $plan);        

            // # Payment definitions for this billing plan.
            $paymentDefinition = new PaymentDefinition();

            $paymentDefinition
                ->setAmount(new Currency($requestData['paymentDefinition']['Amount']));
            array_pop($requestData['paymentDefinition']);
            $this->setArrayToMethods(array_filter($requestData['paymentDefinition']), $paymentDefinition); 


            // Charge Models
            $chargeModel = new ChargeModel();
            $chargeModel->setAmount(new Currency($requestData['chargeModel']['Amount']));
            array_pop($requestData['chargeModel']);
            $this->setArrayToMethods(array_filter($requestData['chargeModel']), $chargeModel); 

            $paymentDefinition->setChargeModels(array($chargeModel));

            $merchantPreferences = new MerchantPreferences();
            $baseUrl = $requestData['baseUrl'];

            $merchantPreferences->setReturnUrl($baseUrl.$requestData['ReturnUrl'])
                ->setCancelUrl($baseUrl.$requestData['CancelUrl'])
                ->setSetupFee(new Currency($requestData['merchant_preferences']['SetupFee']));      
            array_pop($requestData['merchant_preferences']);
            $this->setArrayToMethods(array_filter($requestData['merchant_preferences']), $merchantPreferences);     

            $plan->setPaymentDefinitions(array($paymentDefinition));
            $plan->setMerchantPreferences($merchantPreferences);

            $output = $plan->create($this->_api_context);
            return $output;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function get_plan($planId){
        try {
            $plan = Plan::get($planId, $this->_api_context);
            return $plan;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function list_plan($parameters){
            try {
                $planList = Plan::all(array_filter($parameters), $this->_api_context);
                return $planList;
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
                return $ex->getData();
            }        
    }
    
    public function update_plan($planId,$parameters){       
        try {
            $patch = new Patch();
            $value = new PayPalModel('{
                       "state":"ACTIVE"
                     }');
            $this->setArrayToMethods(array_filter($parameters, $patch)) 
                 ->setValue($value);
            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);
            $createdPlan->update($patchRequest, $this->_api_context);
            $plan = Plan::get($planId, $this->_api_context);
            return $plan;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function setArrayToMethods($array, $object) {
        foreach ($array as $key => $val) {
            $method = 'set' . $key;
            if (!empty($val)) {
                if (method_exists($object, $method)) {
                    $object->$method($val);
                }
            }
        }
        return TRUE;
    }

}

?>