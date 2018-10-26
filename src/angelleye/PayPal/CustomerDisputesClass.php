<?php

namespace angelleye\PayPal;

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

use PayPal\Common\PayPalModel;
use PayPal\Common\PayPalResourceModel;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use PayPal\Validation\ArgumentValidator;

/**
 * CustomerDisputesClass
 * This class includes functionalities to call the APIs for Customer Disputes API
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */

class CustomerDisputesClass extends PayPalResourceModel {
        
    /**
     * Lists disputes with a full or summary set of details. 
     *
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public static function list_disputes($params,$apiContext = null, $restCall = null) {
        
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');        
        $payLoad = "";
        $allowedParams = array(
            'start_time' => 1,
            'disputed_transaction_id' => 1,
            'page_size' => 1,
            'next_page_token' => 1,
            'dispute_state' => 1            
        );
        $json = self::executeCall(
            "/v1/customer/disputes" . "?" . http_build_query(array_intersect_key($params, $allowedParams)), 
            "GET", 
            $payLoad,
            null,
            $apiContext,
            $restCall
        );        
        $object = new CustomerDisputesClass();        
        return $object->fromJson($json);
    }
    
    /**
     * Shows details for a dispute, by ID.
     *
     * @param string $dispute_id
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function get($dispute_id, $apiContext = null, $restCall = null){
        
        ArgumentValidator::validate($dispute_id, 'dispute_id');
        $payLoad = "";
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id,
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json);
    }
    
    /**
     * Accepts liability for a claim, by ID, which closes the dispute in the customer’s favor. 
     * PayPal automatically refunds money to the customer from the merchant's account.
     *
     * @param string $dispute_id
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function dispute_accept_claim($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/accept-claim",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json);
    }
    
    /**
     * Settle dispute
     * Settles a dispute in either the customer's or merchant's favor.
     * Merchants can make this call in the sandbox to complete end-to-end dispute resolution testing, 
     * which mimics the dispute resolution that PayPal agents normally complete. 
     * To make this call, the dispute status must be UNDER_REVIEW.
     *
     * @param string $dispute_id
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function adjudicate($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/adjudicate",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json);    
    }
    
    /**
     * Appeals a dispute, by ID. 
     * To appeal a dispute, use the appeal link in the HATEOAS links from the show dispute details response. 
     * If this link does not appear, you cannot appeal the dispute. 
     * Submit new evidence as a document or notes in the JSON request body.
     *
     * @param string $dispute_id
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function appeal($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/appeal",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json);    
    }
    
    /**
     * Escalate dispute to a claim.
     * Escalates the dispute, by ID, to a PayPal claim. To make this call, the stage in the dispute lifecycle must be INQUIRY.
     *
     * @param string $dispute_id
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function escalate($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/escalate",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json); 
    }

    /**
     * Make offer to resolve dispute.
     * Makes an offer to the other party to resolve a dispute, by ID.
     * To make this call, the stage in the dispute lifecycle must be INQUIRY.
     * If the customer accepts the offer, PayPal automatically makes a refund.
     *
     * @param string $dispute_id
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function make_offer($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/make-offer",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json); 
    }
    
    /**
     * Provides evidence for a dispute, by ID. 
     *
     * @param string $dispute_id
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function provide_evidence($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/provide-evidence",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json); 
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
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function require_evidence($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/require-evidence",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json); 
    }

    
    /**
     * Sends a message to the other party in the dispute, by ID.
     *
     * @param string $dispute_id
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function send_message($dispute_id,$params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');       
        $payLoad = json_encode($params);    
        $json = self::executeCall(
            "/v1/customer/disputes/".$dispute_id."/send-message",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CustomerDisputesClass();        
        return $ret->fromJson($json);
    }
    
}
