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
 * PayPalSyncClass.
 * PayPal Sync API to get the history of transactions for a PayPal account.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class PayPalSyncClass extends PayPalResourceModel {
    
    /**
     * Lists transactions.
     * Specify one or more query parameters to filter the transaction that appear in the response.
     *
     * @param Array $params
     * @param PayPal\Rest\ApiContext $apiContext
     * @param PayPal\Transport\PayPalRestCall $restCall
     * @return $this
     */
    public static function transactions($params,$apiContext = null, $restCall = null) {
        
        if (is_null($params)) {
            $params = array();
        }
        ArgumentValidator::validate($params, 'params');        
        $payLoad = "";
        $allowedParams = array(
            'transaction_id' => 1,
            'transaction_type' => 1,
            'transaction_status' => 1,
            'transaction_amount' => 1,
            'transaction_currency' => 1,
            'start_date' => 1,
            'end_date' => 1,
            'payment_instrument_type' => 1,
            'store_id' => 1,
            'terminal_id' => 1,
            'fields' => 1,
            'balance_affecting_records_only' => 1,
            'page_size' => 1,
            'page' => 1
        );
        $json = self::executeCall(
            "/v1/reporting/transactions" . "?" . http_build_query(array_intersect_key($params, $allowedParams)), 
            "GET", 
            $payLoad,
            null,
            $apiContext,
            $restCall
        );        
        $object = new PayPalSyncClass();        
        return $object->fromJson($json);
    }
            
    
}