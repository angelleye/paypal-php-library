<?php namespace angelleye\PayPal\rest\notifications;

use \angelleye\PayPal\RestClass;
use PayPal\Api\Webhook;
use PayPal\Api\WebhookEventType;


class NotificationsAPI extends RestClass {

    private $_api_context;
    public function __construct($configArray) {        
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }    
    
    public function create_webhook($requestData){
        
        $webhook = new Webhook();
        $webhook->setUrl($requestData['Url']);
        
        $webhookEventTypes = array();
        foreach ($requestData['EventTypes'] as $value) {            
            $type = new WebhookEventType();
            $type->setName($value['Name']);
            $webhookEventTypes[] = $type;                        
        }
        
        $webhook->setEventTypes($webhookEventTypes);
        
        $request = clone $webhook;
        
        try {
            $output = $webhook->create($this->_api_context);
            
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBHOOK']=$output->toArray();
            $returnArray['RAWREQUEST']=$request->toJSON();
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }        
    }
    
    public function list_all($requestData){
        try {
            $output = \PayPal\Api\Webhook::getAllWithParams($requestData,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBHOOKS']=$output->toArray();
            $returnArray['RAWREQUEST']= json_encode($requestData);
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $this->createErrorResponse($ex);
        }                
    }    
    
    public function showByID($webhook_id){
        try {
            $output = \PayPal\Api\Webhook::get($webhook_id,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBHOOK']=$output->toArray();
            $returnArray['RAWREQUEST']= '{webhook_id : '.$webhook_id.'}';
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    public function delete_webhook($webhook_id){
        $webhook = new Webhook();
        $webhook->setId($webhook_id);
        try {
            $output = $webhook->delete($this->_api_context);           
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBHOOK_DELETE']=$output;
            $returnArray['RAWREQUEST']= '{webhook_id : '.$webhook_id.'}';
            $returnArray['RAWRESPONSE']=$output;
            return $returnArray;
        } catch (Exception $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    
}
