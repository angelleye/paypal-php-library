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
 * EventTypesClass is responsible for Webhooks notifications.
 * The PayPal REST APIs use webhooks for event notification.
 * Webhooks are HTTP callbacks that receive notification messages for events. 
 * After you configure a webhook listener for your app, you can create a webhook, 
 * which subscribes the webhook listener for your app to events.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class EventTypesClass extends PayPalResourceModel {

    /**
     *  List events to which webhooks can subscribe.
     *
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function get_all( $apiContext = null, $restCall = null) {
        
        $payLoad = "";
        $json = self::executeCall(
                "/v1/notifications/webhooks-event-types",
                "GET",
                $payLoad,
                null,
                $apiContext,
                $restCall
        );
        $ret = new EventTypesClass();
        return $ret->fromJson($json);
    }
    
    /**
     * Lists event subscriptions for a webhook, by ID.
     *
     * @param string $webhook_id
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function get_by_id($webhook_id,$apiContext = null, $restCall = null) {
        
        ArgumentValidator::validate($webhook_id, 'webhook_id');
         
        $payLoad = "";
        $json = self::executeCall(
                "/v1/notifications/webhooks/".$webhook_id."/event-types",
                "GET",
                $payLoad,
                null,
                $apiContext,
                $restCall
        );
        $ret = new EventTypesClass();
        return $ret->fromJson($json);
    }

    /**
     * Verifies a webhook signature.
     *
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function verify_webhook_signature_api($params,$apiContext = null, $restCall = null) {
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');        
        $payLoad = json_encode($params);      
        $json = self::executeCall(
            "/v1/notifications/verify-webhook-signature", 
            "POST", 
            $payLoad,
            null,
            $apiContext,
            $restCall
        );        
        $object = new EventTypesClass();
        return $object->fromJson($json);
    }
    
    /**
     * Simulates a webhook event.
     *
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public function simulate_webhook_event_api($params,$apiContext = null, $restCall = null){
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');        
        $payLoad = json_encode($params);      
        $json = self::executeCall(
            "/v1/notifications/simulate-event", 
            "POST", 
            $payLoad,
            null,
            $apiContext,
            $restCall
        );        
        $object = new EventTypesClass();
        return $object->fromJson($json);
    }

}
