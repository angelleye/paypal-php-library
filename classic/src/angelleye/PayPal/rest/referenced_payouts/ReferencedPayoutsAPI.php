<?php

namespace angelleye\PayPal\rest\referenced_payouts;

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

use \angelleye\PayPal\ReferencedPayoutsClass;
use \angelleye\PayPal\RestClass;

/**
 * ReferencedPayoutsAPI.
 * This class is responsible for Referenced Payouts APIs & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class ReferencedPayoutsAPI extends RestClass {

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
     * Creates a referenced batch payout for asynchronous, offline processing. Include a sender_batch_header object and an items array in the request URI.
     *
     * @param array $parameters
     * @return array|object
     */
    public function CreateReferencedBatchPayout($parameters) {
         $referancePayoutObject = new ReferencedPayoutsClass();
        try {
            $params = array_filter($parameters);
            $requestArray = json_encode($params);
            $payouts = $referancePayoutObject->referenced_payouts($params, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYOUTS'] = $payouts->toArray();
            $returnArray['RAWREQUEST'] = $requestArray;
            $returnArray['RAWRESPONSE'] = $payouts->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

}
