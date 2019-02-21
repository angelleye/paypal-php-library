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
     * Creates an order.
     *
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public static function create_order($params,$apiContext = null, $restCall = null) {
        
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
     * Get Approval Link
     *
     * @return null|string
     */
    public function getApprovalLink()
    {
        return $this->getLink('approve');
    }
}
