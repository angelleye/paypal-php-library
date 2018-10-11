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
    
    public function update_webhook($webhook_id,$requestData){
            $webhook = new Webhook();        
            $pathRequest = new \PayPal\Api\PatchRequest();
        try {
            $webhook->setId($webhook_id);            
            $i=0;
            foreach ($requestData as $value) {  
                if(is_array($value['Value'])){
                    if(!empty($value['Op']) && !empty($value['Path']) && count($value['Value'])>3){
                        $ob=(object)  array_filter($value['Value']);
                        $pathOperation = new \PayPal\Api\Patch();
                        $pathOperation->setOp($value['Op'])
                                         ->setPath($value['Path'])
                                         ->setValue($ob);
                        $pathRequest->addPatch($pathOperation);
                        $i++;
                    }
                }
                else{
                    if(!empty($value['Op']) && !empty($value['Path']) && !empty($value['Value'])){
                        $pathOperation = new \PayPal\Api\Patch();
                        $pathOperation->setOp($value['Op'])
                                         ->setPath($value['Path']);
                        if($value['Path'] == '/event_types'){
                            $pathOperation->setValue(json_decode('[{"name":"'.$value['Value'].'"}]'));
                        }
                        else{
                            $pathOperation->setValue($value['Value']);
                        }
                        $pathRequest->addPatch($pathOperation);
                        $i++;
                    }
                }               
            } 
           
            if($i>0) {               
                $output = $webhook->update($pathRequest,$this->_api_context);
                $returnArray['RESULT'] = 'Success';
                $returnArray['WEBHOOK']=$output->toArray();         
                $returnArray['RAWREQUEST']= $pathRequest->toJSON();
                $returnArray['RAWRESPONSE']=$output->toJSON();
                return $returnArray;                
            }
            else{
                return "Fill Atleast One Array Field/Element";
            }
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    public function search_webhook_events($params){
        $params = array_filter($params);
        try {
            $output = \PayPal\Api\WebhookEvent::all($params, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['EVENTS']=$output->toArray();         
            $returnArray['RAWREQUEST']= $params;
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    
}
