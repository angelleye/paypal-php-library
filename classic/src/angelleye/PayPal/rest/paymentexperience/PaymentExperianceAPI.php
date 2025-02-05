<?php 
namespace angelleye\PayPal\rest\paymentexperience;

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

use \PayPal\Api\FlowConfig;
use \PayPal\Api\InputFields;
use \PayPal\Api\Patch;
use \PayPal\Api\Presentation;
use \PayPal\Api\WebProfile;
use \angelleye\PayPal\RestClass;

/**
 * PaymentExperianceAPI.
 * This class is responsible for Payment Experiance APIs & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class PaymentExperianceAPI extends RestClass {   

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
     * Creates a web experience profile. In the JSON request body, specify the profile name and details.
     *
     * @param array $requestData
     * @return array|object
     */
    public function CreateWebProfile($requestData){

        try {
            
            // #### Payment Web experience profile resource
            $webProfile = new WebProfile();

            if(isset($requestData['FlowConfig'])){
                $flowConfig = new FlowConfig();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['FlowConfig']), $flowConfig);
                $webProfile->setFlowConfig($flowConfig);
            }

            if(isset($requestData['presentation'])){
                $presentation = new Presentation();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['presentation']), $presentation);
                $webProfile->setPresentation($presentation);
            }

            if(isset($requestData['InputFields'])){
                $inputFields = new InputFields();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['InputFields']), $inputFields);
                $webProfile->setInputFields($inputFields);
            }
            if(isset($requestData['WebProfile'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['WebProfile']), $webProfile);
            }
            $requestArray = clone $webProfile;
            $createProfileResponse = $webProfile->create($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBPROFILE'] = $createProfileResponse->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$createProfileResponse->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Shows details for a web experience profile, by ID.
     *
     * @param string $profileId
     * @return array|object
     */
    public function GetWebProfile($profileId){
        try {
            $webProfile = WebProfile::get($profileId,$this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBPROFILE'] = $webProfile->toArray();
            $returnArray['RAWREQUEST']='{id:'.$profileId.'}';
            $returnArray['RAWRESPONSE']=$webProfile->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Lists the latest 20 web experience profiles for a merchant or subject. To show details for these or additional profiles, you can show web experience profile details by ID.
     *
     * @return array|object
     */
    public function ListWebProfiles(){
        try {
            $returnArray['RESULT'] = 'Success';
            $returnArray['LIST'] = WebProfile::get_list($this->_api_context);
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Deletes a web experience profile, by ID.
     *
     * @param string $profileId
     * @return array|object
     */
    public function DeleteWebProfile($profileId){
        try {
            $webProfile = new WebProfile();
            $webProfile->setId($profileId);
            $returnArray['RESULT'] = 'Success';
            $returnArray['DELETE_RESPONSE'] = $webProfile->delete($this->_api_context);
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Partially-updates a web experience profile, by ID.
     *
     * @param array $patchArray
     * @param string $profileID
     * @return array|object
     */
    public function PartiallyUpdateWebProfile($patchArray,$profileID){

        $webProfile = new WebProfile();
        $webProfile->setId($profileID);
        
        $patches=array();
        
        foreach ($patchArray as $patch){
           if($patch['Op']=='remove'){
              unset($patch['Value']);
           }
            $patchOperation = new Patch();
            $this->setArrayToMethods($patch, $patchOperation);
            array_push($patches, $patchOperation);
        }              
        try {
            // Execute the partial update, to carry out these two operations on a given web profile object
            if ($webProfile->partial_update($patches, $this->_api_context)) {                
                $webProfile = WebProfile::get($webProfile->getId(), $this->_api_context);                
                $returnArray['RESULT'] = 'Success';
                $returnArray['WEBPROFILE'] = $webProfile->toArray();
                $returnArray['RAWREQUEST']=$patches;
                $returnArray['RAWRESPONSE']=$webProfile->toJSON();
                return $returnArray;                
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Updates a web experience profile, by ID.
     *
     * @param array $requestData
     * @param string $profileID
     * @return array|object
     */
    public function UpdateWebProfile($requestData,$profileID){
        
        try {            
            // #### Payment Web experience profile resource
            $webProfile = WebProfile::get($profileID, $this->_api_context);
            
            if(isset($requestData['FlowConfig'])){
                $flowConfig = $webProfile->getFlowConfig();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['FlowConfig']), $flowConfig);
                $webProfile->setFlowConfig($flowConfig);
            }

            if(isset($requestData['presentation'])){
                $presentation = $webProfile->getPresentation();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['presentation']), $presentation);
                $webProfile->setPresentation($presentation);
            }

            if(isset($requestData['InputFields'])){
                $inputFields = $webProfile->getInputFields();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['InputFields']), $inputFields);
                $webProfile->setInputFields($inputFields);
            }
            
            if(isset($requestData['WebProfile'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['WebProfile']), $webProfile);
            }
            $requestArray = clone $webProfile;
            $webProfile->update($this->_api_context);
            $updatedWebProfile = WebProfile::get($profileID, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['WEBPROFILE'] = $updatedWebProfile->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$updatedWebProfile->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
}
