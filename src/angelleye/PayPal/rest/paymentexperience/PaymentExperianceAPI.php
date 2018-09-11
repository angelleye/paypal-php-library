<?php 
namespace angelleye\PayPal\rest\paymentexperience;

use \PayPal\Api\FlowConfig;
use \PayPal\Api\InputFields;
use \PayPal\Api\Patch;
use \PayPal\Api\Presentation;
use \PayPal\Api\WebProfile;
use \angelleye\PayPal\RestClass;

class PaymentExperianceAPI extends RestClass {   

    public function __construct($configArray) {        
        parent::__construct($configArray);
    }
    
    public function create_web_profile($requestData){

        try {
            
            // #### Payment Web experience profile resource
            $webProfile = new WebProfile();

            if($this->checkEmptyObject($requestData['FlowConfig'])){
                $flowConfig = new FlowConfig();
                $this->setArrayToMethods(array_filter($requestData['FlowConfig']), $flowConfig);
                $webProfile->setFlowConfig($flowConfig);
            }

            if($this->checkEmptyObject($requestData['presentation'])){
                $presentation = new Presentation();
                $this->setArrayToMethods(array_filter($requestData['presentation']), $presentation);
                $webProfile->setPresentation($presentation);
            }

            if($this->checkEmptyObject($requestData['InputFields'])){
                $inputFields = new InputFields();
                $this->setArrayToMethods(array_filter($requestData['InputFields']), $inputFields);
                $webProfile->setInputFields($inputFields);
            }
            if($this->checkEmptyObject($requestData['WebProfile'])){
                $this->setArrayToMethods(array_filter($requestData['WebProfile']), $webProfile);
            }
            $requestArray = clone $webProfile;
            $createProfileResponse = $webProfile->create($this->_api_context);
            $returnArray=$createProfileResponse->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$createProfileResponse->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function get_web_profile($profileId){
        try {
            $webProfile = WebProfile::get($profileId,$this->_api_context);
            $returnArray=$webProfile->toArray();
            $returnArray['RAWREQUEST']='{id:'.$profileId.'}';
            $returnArray['RAWRESPONSE']=$webProfile->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function list_web_profiles(){ 
        try {
            return WebProfile::get_list($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function delete_web_profile($profileId){
        try {
            $webProfile = new WebProfile();
            $webProfile->setId($profileId);
            return $webProfile->delete($this->_api_context);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
                $returnArray=$webProfile->toArray();
                $returnArray['RAWREQUEST']=$patches;
                $returnArray['RAWRESPONSE']=$webProfile->toJSON();
                return $returnArray;                
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function update_web_profile($requestData,$profileID){
        
        try {            
            // #### Payment Web experience profile resource
            $webProfile = $this->get_web_profile($profileID);
            
            if($this->checkEmptyObject($requestData['FlowConfig'])){
                $flowConfig = $webProfile->getFlowConfig();
                $this->setArrayToMethods(array_filter($requestData['FlowConfig']), $flowConfig);
                $webProfile->setFlowConfig($flowConfig);
            }

            if($this->checkEmptyObject($requestData['presentation'])){
                $presentation = $webProfile->getPresentation();
                $this->setArrayToMethods(array_filter($requestData['presentation']), $presentation);
                $webProfile->setPresentation($presentation);
            }

            if($this->checkEmptyObject($requestData['InputFields'])){
                $inputFields = $webProfile->getInputFields();
                $this->setArrayToMethods(array_filter($requestData['InputFields']), $inputFields);
                $webProfile->setInputFields($inputFields);
            }
            if($this->checkEmptyObject($requestData['WebProfile'])){
                $this->setArrayToMethods(array_filter($requestData['WebProfile']), $webProfile);
            }
            $requestArray = clone $webProfile;
            $webProfile->update($this->_api_context);
            $updatedWebProfile = WebProfile::get($profileID, $this->_api_context);
            $returnArray=$updatedWebProfile->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$updatedWebProfile->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }        
    }
}
