<?php 
namespace angelleye\PayPal;

class RestClass
{
    private $_api_context;
    
    public function __construct($configArray){        
        $this->_api_context = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'], $configArray['ClientSecret'])
        );
        $this->set_partner_attribution_id('AngellEYE_PHPClass');
    }
        
    public function get_api_context(){
        return $this->_api_context;
    }
    
    public function set_partner_attribution_id($source){
        $this->_api_context->setConfig(array('http.headers.PayPal-Partner-Attribution-Id' => $source));
    }
        
    public function checkEmptyObject($array){
        $filter_array = array_filter($array);
        if(count($filter_array) > 0){
            return $filter_array;
        }
        else {
            return array();
        }
    }
    
    public function setArrayToMethods($array, $object) {
        if(!empty($array)){
            foreach ($array as $key => $val) {                
                if (!empty($val)) {
                    $method = 'set' . $key;
                    if (method_exists($object, $method)) {
                        $object->$method($val);
                    }
                }
            }
            return TRUE;
        }
        return;
    }
}