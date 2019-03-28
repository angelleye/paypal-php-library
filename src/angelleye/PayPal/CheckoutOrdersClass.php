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

class CheckoutOrdersClass extends PayPalResourceModel {


    /**
     * Get Approval Link
     *
     * @return null|string
     */
    public function getApprovalLink()
    {
        return $this->getLink('approve');
    }

    /**
     * Get Capture ID from the Order Details
     * @return string|null
     */
    public function getCaptureId(){

        if (isset($this->purchase_units)){
            foreach($this->purchase_units as $purchase_unit)
            {
                if(isset($purchase_unit['payments']['captures'])){
                    foreach($purchase_unit['payments']['captures'] as $capture)
                    {
                        // if you want full capture object then return $capture from here
                        return isset($capture['id']) ? $capture['id'] : null;
                    }
                }
            }
        }
        return null;
    }

    /**
     * Get auth details from Order details.
     * @return array|null
     */
    public function getAuthId(){
        if (isset($this->purchase_units)){
            foreach($this->purchase_units as $purchase_unit)
            {
                if(isset($purchase_unit['payments']['authorizations'])){
                    foreach($purchase_unit['payments']['authorizations'] as $authorization)
                    {
                        // if you want full capture object then return $capture from here
                        $data = array(
                            'id' => isset($authorization['id']) ? $authorization['id'] : null,
                            'status' => isset($authorization['status']) ? $authorization['status'] : null
                        );
                        return $data;
                    }
                }
            }
        }
        return null;
    }

    /**
     * Creates an order.
     *
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPal\Transport\PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls.
     * @return $this
     */
    public function create_order($params,$apiContext = null, $restCall = null) {
        
        if (is_null($params)) {
            $params = array();
        }

        ArgumentValidator::validate($params, 'params');
        $payLoad = json_encode($params);

        $json = self::executeCall(
            "/v2/checkout/orders",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $object = new CheckoutOrdersClass();
        return $object->fromJson($json);
    }


    /**
     * Captures a payment for an order, by ID. To use this call, the original payment call must specify an intent of `order`. In the JSON request body, include the payment amount and indicate whether this capture is the final capture for the authorization.
     *
     * @param string $order_id
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return $this
     */
    public function capture($order_id, $apiContext = null, $restCall = null){

        ArgumentValidator::validate($order_id, 'order_id');
        $payLoad = "";
        $json = self::executeCall(
            "/v2/checkout/orders/$order_id/capture",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        return $ret->fromJson($json);
    }

    /**
     * Authorizes an order, by ID.
     * @param string $order_id
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return CheckoutOrdersClass
     */
    public function authorize($order_id, $apiContext = null, $restCall = null){
        ArgumentValidator::validate($order_id, 'order_id');
        $payLoad = "";
        $json = self::executeCall(
            "/v2/checkout/orders/$order_id/authorize",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        return $ret->fromJson($json);
    }

    /**
     * Shows details for an order, by ID.
     *
     * @param string $orderId
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return CheckoutOrdersClass
     */
    public function get($order_id, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($order_id, 'order_id');
        $payLoad = "";
        $json = self::executeCall(
            "/v2/checkout/orders/$order_id",
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Shows details for an authorization, by ID.
     *
     * @param string $authorization_id
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return $this
     */
    public function get_authorization($authorization_id, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($authorization_id, 'authorization_id');
        $payLoad = "";
        $json = self::executeCall(
            "/v2/payments/authorizations/$authorization_id",
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Shows details for a capture, by ID.
     *
     * @param string $capture_id
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return CheckoutOrdersClass
     */
    public function get_capture($capture_id, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($capture_id, 'capture_id');
        $payLoad = "";
        $json = self::executeCall(
            "/v2/payments/captures/$capture_id",
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Captures and processes an authorization, by ID. To use this call, the original payment call must specify an intent of `authorize`.
     * @param string $authorization_id
     * @param array $params
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return CheckoutOrdersClass
     */
    public function capture_authorization($authorization_id,$params,$apiContext = null, $restCall = null){
        ArgumentValidator::validate($authorization_id, 'authorization_id');
        ArgumentValidator::validate($params, 'params');
        $payLoad = json_encode($params);
        $json = self::executeCall(
            "/v2/payments/authorizations/$authorization_id/capture",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Reauthorizes a PayPal account payment, by authorization ID. To ensure that funds are still available, reauthorize a payment after the initial three-day honor period. Supports only the `amount` request parameter.
     *
     * @param string $authorization_id
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return CheckoutOrdersClass
     */
    public function reauthorize($authorization_id,$params,$apiContext = null, $restCall = null){
        ArgumentValidator::validate($authorization_id, 'authorization_id');
        ArgumentValidator::validate($params, 'params');
        $payLoad = json_encode($params);
        $json = self::executeCall(
            "/v2/payments/authorizations/$authorization_id/reauthorize",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Voids, or cancels, an authorization, by ID. You cannot void a fully captured authorization.
     *
     * @param string $authorization_id
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return CheckoutOrdersClass
     */
    public function void($authorization_id,$apiContext = null, $restCall = null){
        ArgumentValidator::validate($authorization_id, 'authorization_id');
        $payLoad = '';
        $json = self::executeCall(
            "/v2/payments/authorizations/$authorization_id/void",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Refunds a captured payment, by ID. Include an `amount` object in the JSON request body.
     * @param string $capture_id
     * @param array $params
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return CheckoutOrdersClass
     */
    public function refund($capture_id,$params,$apiContext = null, $restCall = null){
        ArgumentValidator::validate($capture_id, 'capture_id');
        ArgumentValidator::validate($params, 'params');
        $payLoad = json_encode($params);
        $json = self::executeCall(
            "/v2/payments/captures/$capture_id/refund",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Shows details for a refund, by ID.
     *
     * @param string $refund_id
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return CheckoutOrdersClass
     */
    public function get_refund_details($refund_id,$apiContext = null, $restCall = null){
        ArgumentValidator::validate($refund_id, 'refund_id');
        $payLoad = '';
        $json = self::executeCall(
            "/v2/payments/refunds/$refund_id",
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        $ret->fromJson($json);
        return $ret;
    }

    /**
     * Updates an order by ID. Updates an order with the CREATED or APPROVED status.
     * @param string $order_id
     * @param array $params
     * @param ApiContext $apiContext
     * @param PayPalRestCall $restCall
     * @return CheckoutOrdersClass
     */
    public function update_order($order_id,$params,$apiContext = null, $restCall = null){
        ArgumentValidator::validate($order_id, 'order_id');
        ArgumentValidator::validate($params, 'params');
        $payLoad = json_encode($params);
        $json = self::executeCall(
            "/v2/checkout/orders/$order_id",
            "PATCH",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new CheckoutOrdersClass();
        $ret->fromJson($json);
        return $ret;
    }
}
