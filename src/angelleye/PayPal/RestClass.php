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
        if(count(array_filter($array)) > 0){
            return TRUE;
        }
        else {
            return FALSE;
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