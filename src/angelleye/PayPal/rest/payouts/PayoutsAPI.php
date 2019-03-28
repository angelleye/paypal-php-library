<?php

namespace angelleye\PayPal\rest\payouts;

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

use PayPal\Api\Currency;
use PayPal\Api\Payout;
use PayPal\Api\PayoutItem;
use PayPal\Api\PayoutSenderBatchHeader;
use \angelleye\PayPal\RestClass;

/**
 * PayoutsAPI.
 * This class is responsible for Payouts APIs & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class PayoutsAPI extends RestClass {

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
     * Creates a payout.
     *
     * @param array $requestData
     * @return array|object
     */
    public function CreateSinglePayout($requestData) {
        
        try {
            $payouts = new Payout();
            $senderBatchHeader = new PayoutSenderBatchHeader();
            if (isset($requestData['batchHeader'])) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['batchHeader']), $senderBatchHeader);
            }
            $senderItem = new PayoutItem();
            if (isset($requestData['PayoutItem'])) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['PayoutItem']), $senderItem);
            }
            $senderItem->setAmount(new Currency(json_encode($requestData['amount'])));

            $senderBatchHeaderArray = $this->checkEmptyObject((array)$senderBatchHeader);
            if (!empty($senderBatchHeaderArray)) {
                $payouts->setSenderBatchHeader($senderBatchHeader);    
            }

            $senderItemArray = $this->checkEmptyObject((array)$senderItem);
            if (!empty($senderItemArray)) {
                $payouts->addItem($senderItem);
            }   
            $requestArray = clone $payouts;
            $output = $payouts->createSynchronous($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYOUT'] = $output->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Creates a batch payout.
     *
     * @param array $requestData
     * @return array|object
     */
    public function CreateBatchPayout($requestData){
        
        try {
            $payouts = new Payout();

            if(isset($requestData['batchHeader'])){
                $senderBatchHeader = new PayoutSenderBatchHeader();
                $this->setArrayToMethods(array_filter($requestData['batchHeader']), $senderBatchHeader);
                if ($this->checkEmptyObject((array)$senderBatchHeader)) {
                    $payouts->setSenderBatchHeader($senderBatchHeader);
                }
            }

            if(isset($requestData['PayoutItem'])){
                foreach ($requestData['PayoutItem'] as $value) {
                    $senderItem = new PayoutItem();
                    if (isset($value['amount'])){
                        $senderItem->setAmount(new Currency(json_encode($value['amount'])));
                        unset($value['amount']);
                    }
                    $this->setArrayToMethods(array_filter($value), $senderItem);
                    $payouts->addItem($senderItem);
                }
            }
            $requestArray = clone $payouts;
            $output = $payouts->create(null,$this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['BATCH_PAYOUT'] = $output->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$output->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Get payout batch status.
     *
     * @param string $payoutBatchId
     * @return array|object
     */
    public function GetPayoutBatchStatus($payoutBatchId){
        try {
            $output = Payout::get($payoutBatchId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['BATCH_STATUS'] = $output->toArray();
            $returnArray['RAWREQUEST']='{id:'.$payoutBatchId.'}';
            $returnArray['RAWRESPONSE']=$output->toJSON();            
           return $returnArray;              
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Get payout item status.
     *
     * @param string $payoutItemId
     * @return array|object
     */
    public function GetPayoutItemStatus($payoutItemId){
        try {
            $output = PayoutItem::get($payoutItemId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYOUT_ITEM'] = $output->toArray();
            $returnArray['RAWREQUEST']='{id:'.$payoutItemId.'}';
            $returnArray['RAWRESPONSE']=$output->toJSON();     
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Cancels an unclaimed transaction. 
     *
     * @param string $payoutItemId
     * @return array|object
     */
    public function CancelPayoutItem($payoutItemId){
        try {
            $PayoutItem = PayoutItem::get($payoutItemId, $this->_api_context);
            if($PayoutItem->transaction_status == 'UNCLAIMED'){
                 $output = PayoutItem::cancel($payoutItemId, $this->_api_context);                 
                 $returnArray['RESULT'] = 'Success';
                 $returnArray['CANCEL_PAYOUT_ITEM'] = $output->toArray();
                 $returnArray['RAWREQUEST']='{id:'.$payoutItemId.'}';
                 $returnArray['RAWRESPONSE']=$output->toJSON();
                 return $returnArray;                                      
            }
            else{
                return "Payout Item Status is not UNCLAIMED";
            }           
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }   
}
