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
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
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
     * Shows details for a dispute, by ID.
     *
     * @param string $dispute_id
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
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

    public function getAuthorization($authorization_id, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($authorization_id, 'authorization_id ');
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

}
