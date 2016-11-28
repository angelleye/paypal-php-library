<?php  
class CreditCardAPI {
    private $_api_context;
    public function __construct($configArray)
    {   // setup PayPal api context 
        $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'],$configArray['ClientSecret'])
            );
    }
    
    public function StoreCreditCard($requestData){
        $creditCard = new \PayPal\Api\CreditCard();
        $this->setArrayToMethods($requestData['creditCard'], $creditCard);
        $this->setArrayToMethods($requestData['payerInfo'], $creditCard);             
        $this->setArrayToMethods(array("BillingAddress"=>array_filter($requestData['billingAddress'])), $creditCard);                   
        $this->setArrayToMethods($requestData['optionalArray'], $creditCard);                     
        try {
            $creditCard->create($this->_api_context);
            return $creditCard;
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();        
        }        
    }
        
    public function listAllCards($requestData) {   
        $creditCard = new \PayPal\Api\CreditCard();
        try {
            $params = array_filter($requestData);
            $cards = $creditCard->all($params, $this->_api_context);
            return $cards;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $ex->getData();                
        }
    }
    
    public function showByID($requestData){        
        $creditCard = new \PayPal\Api\CreditCard();
            try {
                $card = $creditCard->get($requestData['credit_card_id'], $this->_api_context);
                return $card;
            } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
                return $ex->getData();                
            }                    
    }
    
    public function deleteByID($requestData){        
        $creditCard = new \PayPal\Api\CreditCard();        
        try {
            $creditCard->setId($requestData['credit_card_id']);
            return $creditCard->delete($this->_api_context);                
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $ex->getData();                
        }
    }
    
    
    public function setArrayToMethods($array,$object){
        foreach ($array as $key => $val){
            $method = 'set'.$key;
            if(!empty($val)){
                if (method_exists($object, $method))
                {                   
                     $object->$method($val);
                }            
            }
        }
        return TRUE;
    }
}
?>