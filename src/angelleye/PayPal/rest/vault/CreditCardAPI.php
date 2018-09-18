<?php namespace angelleye\PayPal\rest\vault;

use \angelleye\PayPal\RestClass;

class CreditCardAPI extends RestClass {

    private $_api_context;
    public function __construct($configArray) {        
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }    
    
    public function StoreCreditCard($requestData){
        $creditCard = new \PayPal\Api\CreditCard();
        
        if (isset($requestData['creditCard'])) {
            $this->setArrayToMethods($this->checkEmptyObject($requestData['creditCard']), $creditCard);
        }
        
        if (isset($requestData['payerInfo'])) {
            $this->setArrayToMethods($this->checkEmptyObject($requestData['payerInfo']), $creditCard);
        }
        
        if (isset($requestData['billingAddress'])) {
            $this->setArrayToMethods(array("BillingAddress"=>$this->checkEmptyObject($requestData['billingAddress'])), $creditCard); 
        }
        if (isset($requestData['optionalArray'])) {
            $this->setArrayToMethods($this->checkEmptyObject($requestData['optionalArray']), $creditCard);                     
        }
        try {
            $requestArray = clone $creditCard;
            $creditCard->create($this->_api_context);                 
            $returnArray['CREDITCARD']=$creditCard->toArray();
            $returnArray['RESULT'] = 'Success';
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$creditCard->toJSON();            
            return $returnArray;
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }        
    }
        
    public function listAllCards($requestData) {   
        $creditCard = new \PayPal\Api\CreditCard();
        try {            
            $params = array_filter($requestData);
            $requestArray = json_encode($params);
            $cards = $creditCard->all($params, $this->_api_context);
            $returnArray=$cards->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$cards->toJSON();            
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);         
        }
    }
    
    public function showByID($requestData){        
        $creditCard = new \PayPal\Api\CreditCard();
            try {
                $requestArray = clone $creditCard->setId($requestData['credit_card_id']);;
                $card = $creditCard->get($requestData['credit_card_id'], $this->_api_context);
                $returnArray=$card->toArray();
                $returnArray['RAWREQUEST']=$requestArray->toJSON();
                $returnArray['RAWRESPONSE']=$card->toJSON();
                return $returnArray;
            } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
                return $this->createErrorResponse($ex);
            }                    
    }
    
    public function deleteByID($requestData){        
        $creditCard = new \PayPal\Api\CreditCard();        
        try {
            $creditCard->setId($requestData['credit_card_id']);
            return $creditCard->delete($this->_api_context);                
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
        
    public function UpdateCreditCard($requestData,$credit_card_id){
            $creditCard = new \PayPal\Api\CreditCard();    
            $pathRequest = new \PayPal\Api\PatchRequest();
        try {
            $creditCard->setId($credit_card_id);
            $id = $creditCard->getId();
            $i=0;
            foreach ($requestData as $value) {  
                if(is_array($value['value'])){
                    if(!empty($value['operation']) && !empty($value['path']) && count($value['value'])>3){
                        $ob=(object)  array_filter($value['value']);
                        $pathOperation = new \PayPal\Api\Patch();
                        $pathOperation->setOp($value['operation'])
                                         ->setPath("/".$value['path'])
                                         ->setValue($ob);
                        $pathRequest->addPatch($pathOperation);
                        $i++;
                    }
                }
                else{
                    if(!empty($value['operation']) && !empty($value['path']) && !empty($value['value'])){
                        $pathOperation = new \PayPal\Api\Patch();
                        $pathOperation->setOp($value['operation'])
                                         ->setPath("/".$value['path'])
                                         ->setValue($value['value']);
                        $pathRequest->addPatch($pathOperation);
                        $i++;
                    }
                }
            } 
            if($i>0) {
               
                $card = $creditCard->update($pathRequest,$this->_api_context);
                $returnArray=$card->toArray();
                $returnArray['RAWREQUEST']= $pathRequest->toJSON();
                $returnArray['RAWRESPONSE']=$card->toJSON();
                return $returnArray;                
            }
            else{
                return "Fill Atleast One Array Field/Element";
            }
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }    
}
