<?php

namespace angelleye\PayPal\rest\billing;

/**
 *	An open source PHP library written to easily work with PayPal's API's
 *	
 *	Email:  service@angelleye.com
 *  Facebook: angelleyeconsulting
 *  Twitter: angelleye
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 * @package			paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 * @link			https://github.com/angelleye/paypal-php-library/
 * @website			http://www.angelleye.com
 * @support         http://www.angelleye.com/product/premium-support/
 * @version			v2.0.4
 * @filesource
*/

use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;
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
use \angelleye\PayPal\RestClass;

/**
 * BillingAPI.
 * This class is responsible for Billing agreement and plan APIs & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class BillingAPI extends RestClass {    
        
    /**
     * Private vairable to fetch and return @PayPal\Rest\ApiContext object.
     *
     * @var \PayPal\Rest\ApiContext $_api_context 
     */
    private $_api_context;

    /**
	 * Constructor
	 *
	 * @access	public
	 * @param	mixed[]	$configArray Array structure providing config data
	 * @return	void
	 */
    public function __construct($configArray) {        
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }
    
    /**
     * Creates a billing plan.
     *
     * @param Array $requestData
     * @return Array|Object
     */
    public function create_plan($requestData){
        
        // ### Create Plan
        try {
            // Create a new instance of Plan object
            $plan = new Plan();
            if(isset($requestData['plan'])){
               $this->setArrayToMethods($this->checkEmptyObject($requestData['plan']), $plan);
            }            
            // # Payment definitions for this billing plan.
            $paymentDefinition = new PaymentDefinition();           
            $paymentDefinition
                ->setAmount(new Currency($requestData['paymentDefinition']['Amount']));
            array_pop($requestData['paymentDefinition']);
            if(!empty($this->checkEmptyObject((array)$paymentDefinition))){
                $this->setArrayToMethods(array_filter($requestData['paymentDefinition']), $paymentDefinition); 
            }
            
            // Charge Models
            $chargeModel = new ChargeModel();
            $chargeModel->setAmount(new Currency($requestData['chargeModel']['Amount']));
            array_pop($requestData['chargeModel']);
            if(!empty($this->checkEmptyObject((array)$chargeModel))){
                $this->setArrayToMethods(array_filter($requestData['chargeModel']), $chargeModel); 
                $paymentDefinition->setChargeModels(array($chargeModel));
            }
            
            $merchantPreferences = new MerchantPreferences();
            $baseUrl = $requestData['baseUrl'];

            $merchantPreferences->setReturnUrl($baseUrl.$requestData['ReturnUrl'])
                ->setCancelUrl($baseUrl.$requestData['CancelUrl']);
            if(!empty($this->checkEmptyObject($requestData['merchant_preferences']['SetupFee']))){
                $merchantPreferences->setSetupFee(new Currency($requestData['merchant_preferences']['SetupFee']));
            }
            array_pop($requestData['merchant_preferences']);
            
            if(isset($requestData['merchant_preferences'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['merchant_preferences']), $merchantPreferences);
            }
            if(!empty($this->checkEmptyObject((array)$paymentDefinition))){
                $plan->setPaymentDefinitions(array($paymentDefinition));
            }
            if(!empty($this->checkEmptyObject((array)$merchantPreferences))){
                $plan->setMerchantPreferences($merchantPreferences);
            }      
            $requestArray= clone $plan;
            $output = $plan->create($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['PLAN'] = $output->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Shows details for a billing plan, by ID.
     *
     * @param string $planId
     * @return Array|Object
     */
    public function get_plan($planId){
        try {
            $plan = Plan::get($planId, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['PLAN'] = $plan->toArray();
            $returnArray['RAWREQUEST']='{id:'.$planId.'}';
            $returnArray['RAWRESPONSE']=$plan->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Lists billing plans.
     * To filter the plans that appear in the response, specify one or more optional query and pagination parameters.
     *
     * @param Array $parameters
     * @return Array|Object
     */
    public function list_plan($parameters){
            try {
                $planList = Plan::all(array_filter($parameters), $this->_api_context);                
                $returnArray['RESULT'] = 'Success';
                $returnArray['PLANS'] = $planList->toArray();
                $returnArray['RAWREQUEST']=  json_encode($parameters);
                $returnArray['RAWRESPONSE']=$planList->toJSON();
                return $returnArray;                
            } catch (\PayPal\Exception\PayPalConnectionException $ex) {
                return $this->createErrorResponse($ex);
            }        
    }
    
    /**
     * Updates fields in a billing plan, by ID.
     *
     * @param string $planId
     * @param Array $items
     * @param string $state
     * @return Array|Object
     */
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
            $requestArray = clone $createdPlan;
            $createdPlan->update($patchRequest, $this->_api_context);
            $plan = Plan::get($planId, $this->_api_context);
                        
            $returnArray['RESULT'] = 'Success';
            $returnArray['PLAN'] = $plan->toArray();
            $returnArray['RAWREQUEST'] =$requestArray;
            $returnArray['RAWRESPONSE']=$plan->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Deletes a plan by ID.
     *
     * @param string $planId
     * @return Array|Object
     */
    public function delete_plan($planId){
        try {
             $createdPlan = new Plan();
             $createdPlan->setId($planId);
             $result = $createdPlan->delete($this->_api_context);
             $returnArray['RESULT'] = 'Success';
             $returnArray['DELETE_PLAN'] = $result->toArray();
             $returnArray['RAWREQUEST']='{id:'.$planId.'}';
             $returnArray['RAWRESPONSE']=$result->toJSON();
             return $returnArray;             
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Creates billing agreement with creditcard.
     *
     * @param Array $requestData
     * @return Array|Object
     */
    public function create_billing_agreement_with_creditcard($requestData){
        
        $agreement = new Agreement();
        if($this->checkEmptyObject($requestData['agreement'])){
            $this->setArrayToMethods(array_filter($requestData['agreement']), $agreement);
        }
                    
        // Add Plan ID
        // Please note that the plan Id should be only set in this case.
        $plan = new Plan();
        $plan->setId($requestData['planId']);
        
        $agreement->setPlan($plan);                              
        
        // Add Payer
        $payer = new Payer();
        if($this->checkEmptyObject($requestData['payer'])){
            $this->setArrayToMethods(array_filter($requestData['payer']), $payer);
        }
        if($this->checkEmptyObject($requestData['payerInfo'])){
            $payer->setPayerInfo(new PayerInfo(array_filter($requestData['payerInfo'])));
        }
   
        // Add Credit Card to Funding Instruments
        $card = new CreditCard();
        if($this->checkEmptyObject($requestData['creditCard'])){
            $this->setArrayToMethods(array_filter($requestData['creditCard']), $card);
        }
        $fundingInstrument = new FundingInstrument();
        if($this->checkEmptyObject((array)$card)){
            $fundingInstrument->setCreditCard($card);
        }
        if($this->checkEmptyObject((array)$fundingInstrument)){
            $payer->setFundingInstruments(array($fundingInstrument));   
        }
        //Add Payer to Agreement
        if($this->checkEmptyObject((array)$payer)){
            $agreement->setPayer($payer);
        }                
        // Add Shipping Address
        if($this->checkEmptyObject($requestData['shippingAddress'])){
            $shippingAddress = new ShippingAddress();
            $this->setArrayToMethods(array_filter($requestData['shippingAddress']), $shippingAddress);
            $agreement->setShippingAddress($shippingAddress);        
        }        
        // ### Create Agreement
        try {
            // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
             $requestArray= clone $agreement;
             $agreement = $agreement->create($this->_api_context);             
             $returnArray['RESULT'] = 'Success';
             $returnArray['AGREEMENT'] = $agreement->toArray();
             $returnArray['RAWREQUEST']=$requestArray;
             $returnArray['RAWRESPONSE']=$agreement->toJSON();
             return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }
    }

    /**
     * Creates billing agreement with PayPal.
     *
     * @param Array $requestData
     * @return Array|Object
     */
    public function create_billing_agreement_with_paypal($requestData){
        
        $agreement = new Agreement();
        if($this->checkEmptyObject($requestData['agreement'])){
            $this->setArrayToMethods(array_filter($requestData['agreement']), $agreement);
        }        
        // Add Plan ID
        // Please note that the plan Id should be only set in this case.
        $plan = new Plan();
        $plan->setId($requestData['planId']);
        
        if($this->checkEmptyObject((array)$plan)){
            $agreement->setPlan($plan);
        }        
        // Add Payer        
        $payer = new Payer();
        if($this->checkEmptyObject($requestData['payer'])){
            $this->setArrayToMethods(array_filter($requestData['payer']), $payer);
        }
        if($this->checkEmptyObject((array)$payer)){
            $agreement->setPayer($payer);
        }
        // Add Shipping Address
        $shippingAddress = new ShippingAddress();
        if($this->checkEmptyObject($requestData['shippingAddress'])){
            $this->setArrayToMethods(array_filter($requestData['shippingAddress']), $shippingAddress);
        }
        if($this->checkEmptyObject((array)$shippingAddress)){
            $agreement->setShippingAddress($shippingAddress);
        }        
        // ### Create Agreement
        try {
            // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
            $requestArray = clone $agreement;
            $result = $agreement->create($this->_api_context);            
            $approvalUrl = $agreement->getApprovalLink();            
            $returnArray['RESULT'] = 'Success';
            $returnArray['ApprovalURL'] = $approvalUrl;                        
            $returnArray['AGREEMENT'] =$result->toArray();            
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$agreement->toJSON();
            return $returnArray;                                
        }  catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }
    }

    /**
     * Shows details for a billing agreement, by ID.
     *
     * @param string $agreementId
     * @return Array|Object
     */
    public function get_billing_agreement($agreementId){
        try {
            $agreement = Agreement::get($agreementId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';        
            $returnArray['AGREEMENT']= $agreement->toArray();            
            $returnArray['RAWREQUEST']='{id:'.$agreementId.'}';
            $returnArray['RAWRESPONSE']=$agreement->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Bills the balance for an agreement, by ID
     *
     * @param string $agreementId
     * @param string $note
     * @return Array|Object
     */
    public function bill_agreement_balance($agreementId,$note){
        try {            
            $agreementStateDescriptor = new AgreementStateDescriptor();
            if(!empty(trim($note))){
                $agreementStateDescriptor->setNote($note);
            }
            $agreement = new Agreement();
            $agreement->setId($agreementId);
            $output = $agreement->billBalance($agreementStateDescriptor, $this->_api_context);
            $returnArray['RESULT'] = 'Success';  
            $returnArray['BILL_AGREEMENT_BALANCE']= $output;
            $returnArray['RAWREQUEST']='{id:'.$agreementId.'}';
            $returnArray['RAWRESPONSE']=$agreement->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Sets the balance for an agreement, by ID.
     *
     * @param string $agreementId
     * @param Array $amountArray
     * @return Array|Object
     */
    public function set_agreement_balance($agreementId,$amountArray){
        try {            
            
            $Currency = new Currency();
            $Currency->setCurrency($amountArray['Currency']);
            $Currency->setValue($amountArray['value']);            
            
            $agreement = new Agreement();
            $agreement->setId($agreementId);
            
            $output = $agreement->setBalance($Currency,$this->_api_context);
            
            $returnArray['RESULT'] = 'Success';  
            $returnArray['AGREEMENT_BALANCE']= $output;
            $returnArray['RAWREQUEST']='{id:'.$agreementId.'}';
            $returnArray['RAWRESPONSE']=$agreement->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Suspends a billing agreement, by ID.
     *
     * @param string $agreementId
     * @param string $note
     * @return Array|Object
     */
    public function suspend_billing_agreement($agreementId,$note){
        try {
            //Create an Agreement State Descriptor, explaining the reason to suspend.
            $agreementStateDescriptor = new AgreementStateDescriptor();
            if(!empty(trim($note))){
                $agreementStateDescriptor->setNote($note);
            }
            $createdAgreement = new Agreement();
            $createdAgreement->setId($agreementId);
            $createdAgreement->suspend($agreementStateDescriptor, $this->_api_context);            
            $agreement = Agreement::get($agreementId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';    
            $returnArray['AGREEMENT']= $agreement->toArray();
            $returnArray['RAWREQUEST']='{id:'.$agreementId.'}';
            $returnArray['RAWRESPONSE']=$agreement->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Reactivates a suspended billing agreement, by ID.
     *
     * @param string $agreementId
     * @param string $note
     * @return Array|Object
     */
    public function reactivate_billing_agreement($agreementId,$note){
        try {
            
            $agreementStateDescriptor = new AgreementStateDescriptor();
            if(!empty(trim($note))){
                $agreementStateDescriptor->setNote($note);
            }
            $suspendedAgreement = new Agreement();
            $suspendedAgreement->setId($agreementId);
            $suspendedAgreement->reActivate($agreementStateDescriptor, $this->_api_context);            
            $agreement = Agreement::get($agreementId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';         
            $returnArray['AGREEMENT']= $agreement->toArray();
            $returnArray['RAWREQUEST']='{id:'.$agreementId.'}';
            $returnArray['RAWRESPONSE']=$agreement->toJSON();
            return $returnArray;             
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }

    /**
     * List transactions for a billing agreement by passing the ID of the agreement, as well as the start and end dates of the range of transactions to list, to the request URI.
     *
     * @param [type] $agreementId
     * @param [type] $params  Parameters for search string.
     * @return Array|Object
     */
    public function search_billing_transactions($agreementId,$params){                
        try {
            $result = Agreement::searchTransactions($agreementId, $params, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';            
            $returnArray['BILLING_TRANSACTIONS'] = $result->toArray();
            $returnArray['RAWREQUEST']='{id:'.$agreementId.'}';
            $returnArray['RAWRESPONSE']=$result->toJSON();
            return $returnArray;             
        }  catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }                
    }
    
    /**
     * Updates details of a billing agreement, by ID.
     *
     * @param string $agreementId
     * @param Array $agreement
     * @return void
     */
    public function update_billing_agreement($agreementId,$agreement){

        if(count(array_filter($agreement['shipping_address'])) == 0){
            unset($agreement['shipping_address']);
        }
        
        try {            
            $patch = new Patch();
            $patch->setOp('replace')
                ->setPath('/')
                ->setValue(json_decode(json_encode(array_filter($agreement))));        

            $patchRequest = new PatchRequest();
            $patchRequest->addPatch($patch);

            $createdAgreement = new Agreement();
            $createdAgreement->setId($agreementId);
            $requestArray= clone $createdAgreement;
            $createdAgreement->update($patchRequest, $this->_api_context);            
            $agreement = Agreement::get($agreementId, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['AGREEMENT']= $agreement->toArray();            
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$agreement->toJSON();
            return $returnArray;            
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Cancels a billing agreement, by ID.
     *
     * @param string $agreementId
     * @param string $note
     * @return boolean
     */
    public function cancel_billing_agreement($agreementId,$note){
        try {
            
            $agreementStateDescriptor = new AgreementStateDescriptor();
            if(!empty(trim($note))){
                $agreementStateDescriptor->setNote($note);
            }
            $calcelAgreement = new Agreement();
            $calcelAgreement->setId($agreementId);
            $requestArray = clone $calcelAgreement;
            $calcelAgreement->cancel($agreementStateDescriptor, $this->_api_context);            
            $agreement = Agreement::get($agreementId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';            
            $returnArray['AGREEMENT']=$agreement->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$agreement->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
}
