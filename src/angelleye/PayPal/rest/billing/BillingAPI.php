<?php

namespace angelleye\PayPal\rest\billing;

use PayPal\Api\Agreement;
use PayPal\Api\ChargeModel;
use PayPal\Api\CreditCard;
use PayPal\Api\Currency;
use PayPal\Api\FundingInstrument;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Payer;
use PayPal\Api\PayerInfo;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Api\ShippingAddress;

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
    
    public function update_plan($planId,$items,$state){       
        try {
            
            $createdPlan = new Plan();
            $createdPlan->setId($planId);

            $patch = new Patch();
            $value = new PayPalModel('{
                       "state":"'.$state.'"
                     }');
            $this->setArrayToMethods(array_filter($items), $patch);
            $patch->setValue($value);                       
            
            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);
            $createdPlan->update($patchRequest, $this->_api_context);
            $plan = Plan::get($planId, $this->_api_context);
            return $plan;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function create_billing_agreement_with_creditcard($requestData){
        
        $agreement = new Agreement();
        $this->setArrayToMethods(array_filter($requestData['agreement']), $agreement);
            
        // Add Plan ID
        // Please note that the plan Id should be only set in this case.
        $plan = new Plan();
        $plan->setId($requestData['planId']);
        
        $agreement->setPlan($plan);                              
        
        // Add Payer
        $payer = new Payer();
        $this->setArrayToMethods(array_filter($requestData['payer']), $payer);       
        
        $payer->setPayerInfo(new PayerInfo(array_filter($requestData['payerInfo'])));

        // Add Credit Card to Funding Instruments
        $card = new CreditCard();
        $this->setArrayToMethods(array_filter($requestData['creditCard']), $card);        
        
        $fundingInstrument = new FundingInstrument();
        $fundingInstrument->setCreditCard($card);
        $payer->setFundingInstruments(array($fundingInstrument));
        //Add Payer to Agreement
        $agreement->setPayer($payer);        
        
        // Add Shipping Address
        $shippingAddress = new ShippingAddress();
        $this->setArrayToMethods(array_filter($requestData['shippingAddress']), $shippingAddress);
        
        $agreement->setShippingAddress($shippingAddress);        
        
        // ### Create Agreement
        try {
            // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
            $agreement = $agreement->create($this->_api_context);
            return $agreement;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $ex->getData();
        }
    }

    public function create_billing_agreement_with_paypal($requestData){
        
        $agreement = new Agreement();
        $this->setArrayToMethods(array_filter($requestData['agreement']), $agreement);

        // Add Plan ID
        // Please note that the plan Id should be only set in this case.
        $plan = new Plan();
        $plan->setId($requestData['planId']);
        
        $agreement->setPlan($plan);

        // Add Payer
        $payer = new Payer();
        $payer = new Payer();
        $this->setArrayToMethods(array_filter($requestData['payer']), $payer);       
        
        $agreement->setPayer($payer);

        // Add Shipping Address
        $shippingAddress = new ShippingAddress();
        $this->setArrayToMethods(array_filter($requestData['shippingAddress']), $shippingAddress);
        $agreement->setShippingAddress($shippingAddress);                

        // ### Create Agreement
        try {
            // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
            $result = $agreement->create($this->_api_context);            
            $approvalUrl = $agreement->getApprovalLink();
            return array('result'=>$result,'Approval URL' => $approvalUrl);
            
        }  catch (\PayPal\Exception\PayPalConnectionException $ex) {
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