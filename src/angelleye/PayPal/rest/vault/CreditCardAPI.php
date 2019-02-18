<?php 

namespace angelleye\PayPal\rest\vault;


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
use PayPal\Api\CreditCard;

/**
 * CreditCardAPI.
 * This class is responsible for CreditCard APIs & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class CreditCardAPI extends RestClass {

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
     * Stores credit card details in the PayPal vault.
     *
     * @param array $requestData
     * @return array|object
     */
    public function StoreCreditCard($requestData){
        $creditCard = new CreditCard();
        
        if (isset($requestData['creditCard'])) {
            $this->setArrayToMethods($this->checkEmptyObject($requestData['creditCard']), $creditCard);
        }
        
        if (isset($requestData['payerInfo'])) {
            $this->setArrayToMethods($this->checkEmptyObject($requestData['payerInfo']), $creditCard);
        }
        
        if (isset($requestData['billingAddress'])) {
            $this->setArrayToMethods(array("BillingAddress"=>$this->checkEmptyObject($requestData['billingAddress'])), $creditCard);
        }
        if (isset($requestData['optionalArray'])) {
            $this->setArrayToMethods($this->checkEmptyObject($requestData['optionalArray']), $creditCard);
        }
        try {
            $requestArray = clone $creditCard;
            $creditCard->create($this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['CREDITCARD']=$creditCard->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$creditCard->toJSON();
            return $returnArray;
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
           return $this->createErrorResponse($ex);
        }        
    }
    
    /**
     * Lists vaulted credit cards.
     *
     * @param array $requestData
     * @return array|object
     */
    public function ListCreditCards($requestData) {
        $creditCard = new \PayPal\Api\CreditCard();
        try {            
            $params = array_filter($requestData);
            $requestArray = json_encode($params);
            $cards = $creditCard->all($params, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['CARDS'] = $cards->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$cards->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);         
        }
    }
    
    /**
     * Shows details for a vaulted credit card, by ID.
     *
     * @param array $requestData
     * @return array|object
     */
    public function GetCardDetails($requestData){
        $creditCard = new CreditCard();
            try {
                $card = $creditCard->get($requestData['credit_card_id'], $this->_api_context);                
                $returnArray['RESULT'] = 'Success';
                $returnArray['CREDITCARD']=$card->toArray();                
                $returnArray['RAWREQUEST']='{id:'.$requestData['credit_card_id'].'}';
                $returnArray['RAWRESPONSE']=$card->toJSON();
                return $returnArray;
            } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
                return $this->createErrorResponse($ex);
            }                    
    }
    
    /**
     * Deletes a vaulted credit card, by ID.
     *
     * @param array $requestData
     * @return array|object
     */
    public function DeleteCreditCard($requestData){
        $creditCard = new \PayPal\Api\CreditCard();        
        try {
            $creditCard->setId($requestData['credit_card_id']);            
            $result = $creditCard->delete($this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DELETE']=$result;
            $returnArray['RAWREQUEST']=$creditCard->toJSON();
            $returnArray['RAWRESPONSE']=$result;
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }
       
    /**
     * Updates information for a vaulted credit card, by ID. 
     *
     * @param array $requestData
     * @param string $credit_card_id
     * @return array|object
     */
    public function UpdateCreditCard($requestData,$credit_card_id){
            $creditCard = new \PayPal\Api\CreditCard();    
            $pathRequest = new \PayPal\Api\PatchRequest();
        try {
            $creditCard->setId($credit_card_id);
            $id = $creditCard->getId();
            $i=0;
            foreach ($requestData as $value) {  
                if(is_array($value['value'])){
                    if(!empty($value['operation']) && !empty($value['path']) && count($value['value'])>3){
                        $ob=(object)  array_filter($value['value']);
                        $pathOperation = new \PayPal\Api\Patch();
                        $pathOperation->setOp($value['operation'])
                                         ->setPath("/".$value['path'])
                                         ->setValue($ob);
                        $pathRequest->addPatch($pathOperation);
                        $i++;
                    }
                }
                else{
                    if(!empty($value['operation']) && !empty($value['path']) && !empty($value['value'])){
                        $pathOperation = new \PayPal\Api\Patch();
                        $pathOperation->setOp($value['operation'])
                                         ->setPath("/".$value['path'])
                                         ->setValue($value['value']);
                        $pathRequest->addPatch($pathOperation);
                        $i++;
                    }
                }
            } 
            if($i>0) {
               
                $card = $creditCard->update($pathRequest,$this->_api_context);
                $returnArray['RESULT'] = 'Success';
                $returnArray['CREDITCARD']=$card->toArray();         
                $returnArray['RAWREQUEST']= $pathRequest->toJSON();
                $returnArray['RAWRESPONSE']=$card->toJSON();
                return $returnArray;                
            }
            else{
                return "Fill Atleast One Array Field/Element";
            }
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }
    }    
}
