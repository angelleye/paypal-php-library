<?php 
namespace angelleye\PayPal\rest\notifications;


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

use \angelleye\PayPal\RestClass;
use PayPal\Api\Webhook;
use PayPal\Api\WebhookEventType;
use PayPal\Api\WebhookEvent;
use PayPal\Api\Notification;
use PayPal\Api\CancelNotification;
use PayPal\Api\VerifyWebhookSignature;

/**
 * NotificationsAPI.
 * This class is responsible for Notifications APIs & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class NotificationsAPI extends RestClass {

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
     * Subscribes your webhook listener to events.
     *
     * @param array $requestData
     * @return Array|Object
     */
    public function CreateWebhook($requestData){
        
        $webhook = new Webhook();
        if(isset($requestData['Url'])){
            $webhook->setUrl($requestData['Url']);
        }

        if(isset($requestData['EventTypes'])){
            $webhookEventTypes = array();
            foreach ($requestData['EventTypes'] as $value) {
                $type = new WebhookEventType();
                $type->setName($value);
                $webhookEventTypes[] = $type;
            }
            $webhook->setEventTypes($webhookEventTypes);
        }
        
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
    
    /**
     * Lists all webhooks for an app.
     *
     * @param array $requestData
     * @return Array|Object
     */
    public function ListWebhooks($requestData){
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
    
    /**
     * Shows details for a webhook, by ID.
     *
     * @param string $webhook_id
     * @return Array|Object
     */
    public function GetWebhook($webhook_id){
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
    
    /**
     * Deletes a webhook, by ID.
     *
     * @param string $webhook_id
     * @return Array|Object
     */
    public function DeleteWebhook($webhook_id){
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
    
    /**
     * Replaces webhook fields with new values. 
     *
     * @param string $webhook_id
     * @param Array $requestData
     * @return Array|Object
     */
    public function UpdateWebhook($webhook_id,$requestData){
            $webhook = new Webhook();        
            $pathRequest = new \PayPal\Api\PatchRequest();
        try {
            $webhook->setId($webhook_id);
            $i=0;
            foreach ($requestData as $value) {                                  
                if(!empty($value['Op']) && !empty($value['Path']) && !empty($value['Value'])){
                    $pathOperation = new \PayPal\Api\Patch();
                    $pathOperation->setOp($value['Op'])
                                        ->setPath($value['Path']);
                    if($value['Path'] == '/event_types'){
                        $webhookEventTypes = array();
                        foreach($value['Value'] as $event){                                                        
                                $type = new WebhookEventType();
                                $type->setName($event);
                                $webhookEventTypes[] = $type->toArray();                            
                        }                        
                        $pathOperation->setValue($webhookEventTypes);
                    }
                    else{                                                
                            $pathOperation->setValue($value['Value']);                        
                    }
                    $pathRequest->addPatch($pathOperation);
                    $i++;
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
    
    /**
     * Lists webhook event notifications. Use query parameters to filter the response.
     *
     * @param Array $params
     * @return Array|Object
     */
    public function SearchWebhookEvents($params){
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
    
    /**
     * Retrieves the Webhooks event resource identified by event_id.
     *
     * @param string $event_id
     * @return Array|Object
     */
    public function EventNotificationDetails($event_id){
        try {
            $output = \PayPal\Api\WebhookEvent::get($event_id, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['EVENT']=$output->toArray();         
            $returnArray['RAWREQUEST']= '{event_id : '.$event_id.'}';
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Resends a webhook event notification, by ID. Any pending notifications are not resent.
     * 
     * @param string $event_id
     * @return Array|Object
     */
    public function ResendEventNotification($event_id){
        $WebhookEvent = new WebhookEvent();
        $WebhookEvent->setId($event_id);
        try {
            $output = $WebhookEvent->resend($this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['EVENT']=$output->toArray();         
            $returnArray['RAWREQUEST']= '{event_id : '.$event_id.'}';
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * List events to which webhooks can subscribe.
     *
     * @return Array|Object
     */
    public function WebhooksEventTypes(){
        $object = new \angelleye\PayPal\EventTypesClass();        
        try {
            $output = $object->get_all($this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['EVENT_TYPES']=$output->toArray();         
            $returnArray['RAWREQUEST']= '';
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Lists event subscriptions for a webhook, by ID.
     *
     * @param string $webhook_id
     * @return Array|Object
     */
    public function WebhooksEventTypesById($webhook_id){
        $object = new \angelleye\PayPal\EventTypesClass();        
        try {
            $output = $object->get_by_id($webhook_id,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['EVENT']=$output->toArray();         
            $returnArray['RAWREQUEST']= '';
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Verifies a webhook signature.
     *
     * @param array $params
     * @return Array|Object
     */
    public function VerifyWebhookSignature($headers,$webhook_id,$request_body){

        try {
            $signatureVerification = new VerifyWebhookSignature();
            $signatureVerification->setAuthAlgo($headers['PAYPAL-AUTH-ALGO']);
            $signatureVerification->setTransmissionId($headers['PAYPAL-TRANSMISSION-ID']);
            $signatureVerification->setCertUrl($headers['PAYPAL-CERT-URL']);            
            $signatureVerification->setWebhookId($webhook_id);
            $signatureVerification->setTransmissionSig($headers['PAYPAL-TRANSMISSION-SIG']);
            $signatureVerification->setTransmissionTime($headers['PAYPAL-TRANSMISSION-TIME']);
            $signatureVerification->setRequestBody($request_body);
            $output = $signatureVerification->post($this->_api_context);
            $status = $output->getVerificationStatus();

            $returnArray['RESULT'] = 'Success';
            $returnArray['STATUS']= $status;
            $returnArray['RAWREQUEST']= '';
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        }
        catch (Exception $ex){
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Simulates a webhook event.
     *
     * @param array $params
     * @return Array|Object
     */
    public function SimulateEvent($params){
        $object = new \angelleye\PayPal\EventTypesClass();
        try {
            $params = array_filter($params);
            $output = $object->simulate_webhook_event_api($params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['EVENT']=$output->toArray();
            $returnArray['RAWREQUEST']= json_encode($params);
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
}
