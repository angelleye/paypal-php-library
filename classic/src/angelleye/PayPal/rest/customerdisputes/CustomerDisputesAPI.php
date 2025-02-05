<?php

namespace angelleye\PayPal\rest\customerdisputes;

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

use PayPal\Api\Plan;
use \angelleye\PayPal\CustomerDisputesClass;
use \angelleye\PayPal\RestClass;

/**
 * CustomerDisputesAPI.
 * This class is responsible for Customer Disputes & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class CustomerDisputesAPI extends RestClass {

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
     * Lists disputes with a full or summary set of details. Default is a summary set of details, which shows the dispute_id, reason, status, dispute_amount, create_time, and update_time fields.
     *
     * @param Array $parameters
     * @return Array|Object
     */
    public function ListDisputes($parameters) {
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $disputes = CustomerDisputesClass::list_disputes($params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTES'] = $disputes->toArray();            
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$disputes->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Shows details for a dispute, by ID.
     *
     * @param string $dispute_id
     * @return Array|Object
     */
    public function ShowDisputeDetails($dispute_id){
        $disputeObject = new CustomerDisputesClass();
        try {
            $dispute = $disputeObject->get($dispute_id,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']='{dispute_id:'.$dispute_id.'}';
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Accepts liability for a claim, by ID.
     *
     * @param string $dispute_id
     * @param Array $parameters
     * @return Array|Object
     */
    public function AcceptClaim($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {
            $params = $this->checkEmptyObject($parameters);            
            $requestArray = json_encode($params);
            $dispute = $disputeObject->dispute_accept_claim($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    /**
     * Settles a dispute in either the customer's or merchant's favor. 
     * Merchants can make this call in the sandbox to complete end-to-end dispute resolution testing, 
     * which mimics the dispute resolution that PayPal agents normally complete. 
     * To make this call, the dispute status must be UNDER_REVIEW.
     *
     * @param string $dispute_id
     * @param Array $parameters
     * @return Array|Object
     */
    public function SettleDispute($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {
            $params = $this->checkEmptyObject($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->adjudicate($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    /**
     * Appeals a dispute, by ID.
     *
     * @param string $dispute_id
     * @param Array $parameters
     * @return Array|Object
     */
    public function DisputesAppeal($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {
            $params = array_map('array_filter', $parameters);
            $params = array_filter($params);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->appeal($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }  
    }
    
    /**
     * Escalates the dispute, by ID, to a PayPal claim. To make this call, the stage in the dispute lifecycle must be INQUIRY.
     *
     * @param string $dispute_id
     * @param Array $parameters
     * @return Array|Object
     */
    public function DisputesEscalate($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->escalate($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Makes an offer to the other party to resolve a dispute, by ID.
     * To make this call, the stage in the dispute lifecycle must be INQUIRY.
     * If the customer accepts the offer, PayPal automatically makes a refund.
     *
     * @param string $dispute_id
     * @param Array $parameters
     * @return Array|Object
     */
    public function DisputesMakeOffer($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->make_offer($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Provides evidence for a dispute, by ID.
     *
     * @param string $dispute_id
     * @param Array $parameters
     * @return Array|Object
     */
    public function ProvideEvidence($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->provide_evidence($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Updates the status of a dispute, by ID, from UNDER_REVIEW to either:
     * 1) WAITING_FOR_BUYER_RESPONSE
     * 2) WAITING_FOR_SELLER_RESPONSE
     * 
     * If action is BUYER_EVIDENCE The state updates to WAITING_FOR_BUYER_RESPONSE.
     * If action is SELLER_EVIDENCE The state updates to WAITING_FOR_SELLER_RESPONSE.
     *
     * @param string $dispute_id
     * @param Array $parameters
     * @return Array|Object
     */
    public function UpdateDisputeStatus($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->require_evidence($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Sends a message to the other party in the dispute, by ID.
     *
     * @param string $dispute_id
     * @param Array $parameters
     * @return Array|Object
     */
    public function SendMessageToOtherParty($dispute_id,$parameters){
        $disputeObject = new CustomerDisputesClass();
        try {            
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $dispute = $disputeObject->send_message($dispute_id,$params,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DISPUTE']=$dispute->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$dispute->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
}
