<?php 
namespace angelleye\PayPal\rest\paymentexperience;

use \PayPal\Api\FlowConfig;
use \PayPal\Api\InputFields;
use \PayPal\Api\Patch;
use \PayPal\Api\Presentation;
use \PayPal\Api\WebProfile;
use \angelleye\PayPal\RestClass;

class PaymentExperianceAPI extends RestClass {   

    private $_api_context;
    public function __construct($configArray) {        
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }
    
    public function create_web_profile($requestData){

        try {
            
            // #### Payment Web experience profile resource
            $webProfile = new WebProfile();

            if(isset($requestData['FlowConfig'])){
                $flowConfig = new FlowConfig();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['FlowConfig']), $flowConfig);
                $webProfile->setFlowConfig($flowConfig);
            }

            if(isset($requestData['presentation'])){
                $presentation = new Presentation();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['presentation']), $presentation);
                $webProfile->setPresentation($presentation);
            }

            if(isset($requestData['InputFields'])){
                $inputFields = new InputFields();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['InputFields']), $inputFields);
                $webProfile->setInputFields($inputFields);
            }
            if(isset($requestData['WebProfile'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['WebProfile']), $webProfile);
            }
            $requestArray = clone $webProfile;
            $createProfileResponse = $webProfile->create($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBPROFILE'] = $createProfileResponse->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$createProfileResponse->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    public function get_web_profile($profileId){
        try {
            $webProfile = WebProfile::get($profileId,$this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBPROFILE'] = $webProfile->toArray();
            $returnArray['RAWREQUEST']='{id:'.$profileId.'}';
            $returnArray['RAWRESPONSE']=$webProfile->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    public function list_web_profiles(){ 
        try {
            $returnArray['RESULT'] = 'Success';
            $returnArray['LIST'] = WebProfile::get_list($this->_api_context);
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    public function delete_web_profile($profileId){
        try {
            $webProfile = new WebProfile();
            $webProfile->setId($profileId);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DELETE_RESPONSE'] = $webProfile->delete($this->_api_context);
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    public function partially_update_webprofile($patchArray,$profileID){

        $webProfile = new WebProfile();
        $webProfile->setId($profileID);
        
        $patches=array();
        
        foreach ($patchArray as $patch){
           if($patch['Op']=='remove'){
              unset($patch['Value']);
           }
            $patchOperation = new Patch();
            $this->setArrayToMethods($patch, $patchOperation);
            array_push($patches, $patchOperation);
        }              
        try {
            // Execute the partial update, to carry out these two operations on a given web profile object
            if ($webProfile->partial_update($patches, $this->_api_context)) {                
                $webProfile = WebProfile::get($webProfile->getId(), $this->_api_context);                
                $returnArray['RESULT'] = 'Success';
                $returnArray['WEBPROFILE'] = $webProfile->toArray();
                $returnArray['RAWREQUEST']=$patches;
                $returnArray['RAWRESPONSE']=$webProfile->toJSON();
                return $returnArray;                
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    public function update_web_profile($requestData,$profileID){
        
        try {            
            // #### Payment Web experience profile resource
            $webProfile = WebProfile::get($profileID, $this->_api_context);
            
            if(isset($requestData['FlowConfig'])){
                $flowConfig = $webProfile->getFlowConfig();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['FlowConfig']), $flowConfig);
                $webProfile->setFlowConfig($flowConfig);
            }

            if(isset($requestData['presentation'])){
                $presentation = $webProfile->getPresentation();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['presentation']), $presentation);
                $webProfile->setPresentation($presentation);
            }

            if(isset($requestData['InputFields'])){
                $inputFields = $webProfile->getInputFields();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['InputFields']), $inputFields);
                $webProfile->setInputFields($inputFields);
            }
            
            if(isset($requestData['WebProfile'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['WebProfile']), $webProfile);
            }
            $requestArray = clone $webProfile;
            $webProfile->update($this->_api_context);
            $updatedWebProfile = WebProfile::get($profileID, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBPROFILE'] = $updatedWebProfile->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$updatedWebProfile->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
}
