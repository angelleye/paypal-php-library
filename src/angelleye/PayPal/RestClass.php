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

/**
 * RestClass
 * This class includes the common configuration functions for the REST APIs bridge classes.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */

class RestClass extends PayPalModel
{
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

    public function __construct($configArray){        
        $this->_api_context = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'], $configArray['ClientSecret'])
        );
        //$this->set_partner_attribution_id('');
        $path = '';
        if (isset($configArray['LogPath'])){
            $path = $configArray['LogPath'].'/PayPal.log';
            if (!file_exists($path)) {
                fopen($path, 'w');
            }
        }

        $mode ='live';
        if(isset($configArray['Sandbox'])){
            if(($configArray['Sandbox'] == true || $configArray['Sandbox'] == 'true')){
                $mode ='sandbox';
            }
        } 

        $this->_api_context->setConfig(
                array(
                  'mode' => $mode,
                  'log.LogEnabled' => isset($configArray['LogResults']) ? $configArray['LogResults'] : false,
                  'log.FileName' => $path,
                  'log.LogLevel' => isset($configArray['LogLevel']) ? $configArray['LogLevel'] : 'INFO'
                )
        );
    }

    /**
     *  Get ApiContext
     *  @access	public
     *  @return \PayPal\Auth\ApiContext
     */
        
    public function get_api_context(){
        return $this->_api_context;
    }
    
    /**
     * PayPal Partner Code
     * 
     * @access	public
     * @param	string $source 
     * @return	void
     */

    public function set_partner_attribution_id($source){
        $this->_api_context->setConfig(array('http.headers.PayPal-Partner-Attribution-Id' => $source));        
    }
    
    /**
     * Set a unique user-generated ID that you can use to enforce idempotency.     
     * Notes:
     *   1) If you omit this header, the risk of duplicate transactions increases.
     *   2) Not all APIs support this header. To determine whether your API supports it, see the API reference for your API.
     * @access	public
     * @param	string $source 
     * @return	void
     */


    public function set_paypal_request_id($source){
        $this->_api_context->setConfig(array('http.headers.PayPal-Request-Id' => $source));
    }    
    
    /**
     *  Method set_prefer
     *  Indicates how the client expects the server to process this request.
     *  To process the request asynchronously, set this header to respond-async.
     *  If you omit this header, the API processes the request synchronously.
     *  For synchronous processing the application may levy additional checks on the number of supported items
     *  in the request and may fail the request if those limits are breached.
     * 
     * @access	public
     * @param	string $source 
     * @return	void
     */
    
    public function set_prefer($source){
        $this->_api_context->setConfig(array('http.headers.Prefer' => $source));
    }

    /**
     *  Checks for the empty array. It also filters the array and remove the empty value key.
     *       
     * @access	public
     * @param	Array $array 
     * @return	void
     */

    public function checkEmptyObject($array){
        $filter_array = array_filter($array);
        if(count($filter_array) > 0){
            return $filter_array;
        }
        else {
            return array();
        }
    }
        
    /**
     *  Build the objects and set methods and values by request array and the class object.
     *       
     * @access	public
     * @param	Array $array 
     * @param	Object $object REST API classes's object.
     * @return	boolean
     */

    public function setArrayToMethods($array, $object) {
        if(!empty($array)){
            foreach ($array as $key => $val) {                
                if (!empty($val)) {
                    $method = 'set' . $key;
                    if (method_exists($object, $method)) {
                        $object->$method($val);
                    }
                }
            }
            return TRUE;
        }
        return;
    }
        
    /**
     *  Builds the Error array/object return in the REST API response.
     *       
     * @access	public
     * @param	mixed[] $ex      
     * @return	Array|Ojbect
     */

    public function createErrorResponse($ex){
        
        $returnArray['RESULT'] = 'Error';
        $errorCode = $ex->getCode();

        $errorArray = json_decode($ex->getData(),true);
        if($errorCode == '401' && isset($errorArray['error']) && isset($errorArray['error_description'])){
            $returnArray['error_type'] = 'AUTHENTICATION_FAILURE';
            $returnArray['error_message'] = 'Authentication failed due to invalid authentication credentials.';
            $returnArray['error_data'] = isset($errorArray['error']) ? $errorArray['error'] : '';
            $returnArray['error_description'] = isset($errorArray['error_description']) ? $errorArray['error_description'] : '';
            $returnArray['error_array'] = $errorArray;
            return $returnArray;
        }
        elseif ($errorCode == '400'){
            $returnArray['error_type'] = 'INVALID_REQUEST';
            $returnArray['error_message'] = 'Request is not well-formed, syntactically incorrect, or violates schema.';
            $returnArray['error_data'] = isset($errorArray['name']) ? $errorArray['name'] : '';
            $returnArray['error_description'] = isset($errorArray['message']) ? $errorArray['message'] : '';
            $returnArray['debug_id'] = isset($errorArray['debug_id']) ? $errorArray['debug_id'] : '';
            $returnArray['information_link'] = isset($errorArray['information_link']) ? $errorArray['information_link'] : '';
            $details = '';
            if (is_array($errorArray['details'])) {
                foreach ($errorArray['details'] as $e) {
                    $details .= $e['field'] . ": \t" . $e['issue'] . "\n\n";
                }
                $returnArray['details'] = $details;
            }
            $returnArray['error_array'] = $errorArray;
            return $returnArray;
        }
        elseif ($errorCode == '404') {
            $returnArray['error_type'] = 'RESOURCE_NOT_FOUND';
            $returnArray['error_message'] = 'The specified resource does not exist.';
        }
        elseif ($errorCode == '403'){
            $returnArray['error_type'] = 'NOT_AUTHORIZED';
            $returnArray['error_message'] = 'Authorization failed due to insufficient permissions.';
        }
        elseif ($errorCode == '405'){
            $returnArray['error_type'] = 'METHOD_NOT_SUPPORTED';
            $returnArray['error_message'] = 'The server does not implement the requested HTTP method.';
        }
        elseif ($errorCode == '406'){
            $returnArray['error_type'] = 'MEDIA_TYPE_NOT_ACCEPTABLE';
            $returnArray['error_message'] = 'The server does not implement the media type that would be acceptable to the client.';
        }
        elseif ($errorCode == '415'){
            $returnArray['error_type'] = 'UNSUPPORTED_MEDIA_TYPE';
            $returnArray['error_message'] = 'The server does not support the request payload’s media type.';
        }
        elseif ($errorCode == '422'){
            $returnArray['error_type'] = 'UNPROCCESSABLE_ENTITY';
            $returnArray['error_message'] = 'The API cannot complete the requested action, or the request action is semantically incorrect or fails business validation.';
        }
        elseif ($errorCode == '429'){
            $returnArray['error_type'] = 'RATE_LIMIT_REACHED';
            $returnArray['error_message'] = 'Too many requests. Blocked due to rate limiting.';
        }
        elseif ($errorCode == '500'){
            $returnArray['error_type'] = 'INTERNAL_SERVER_ERROR';
            $returnArray['error_message'] = 'An internal server error has occurred.';
        }
        elseif ($errorCode == '503'){
            $returnArray['error_type'] = 'SERVICE_UNAVAILABLE';
            $returnArray['error_message'] = 'Service Unavailable.';
        }
        else{
            $returnArray['error_type'] = 'NOT_SPECIFIED';
            $returnArray['error_message'] = 'Error not specified.';
            $returnArray['errors'] = $ex->getData();
            return $returnArray;
        }

        $returnArray['error_data'] = isset($errorArray['name']) ? $errorArray['name'] : '';
        $returnArray['error_description'] = isset($errorArray['message']) ? $errorArray['message'] : '';
        $returnArray['debug_id'] = isset($errorArray['debug_id']) ? $errorArray['debug_id'] : '';
        $returnArray['information_link'] = isset($errorArray['information_link']) ? $errorArray['information_link'] : '';
        $returnArray['error_array'] = $errorArray;

        return $returnArray;
    }
    
    /**
     * Checks if the string is json or not.
     *
     * @param String $string
     * @return boolean
     */
    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
