<?php 

namespace angelleye\PayPal\rest\identity;

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

use PayPal\Api\OpenIdSession;
use PayPal\Api\OpenIdTokeninfo;;
use PayPal\Api\OpenIdUserinfo;
use PayPal\Exception\PayPalConnectionException;


/**
 * IdentityAPI.
 * This class is responsible for Identity APIs & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */

class IdentityAPI {

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
    public function __construct($configArray)
    {   // setup PayPal api context 
        $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'],$configArray['ClientSecret'])
            );
    }
    
    /**
     * Obtain User Consent URL.
     *
     * @param Array $requestData
     * @return Array|Object
     */
    public function GetUserConsentURL($requestData){
        //Get Authorization URL returns the redirect URL that could be used to get user's consent
        $redirectUrl = OpenIdSession::getAuthorizationUrl(
            $requestData['redirectUri'],$requestData['scope'],
            null,
            null,
            null,
            $this->_api_context
        );
        $returnArray['RESULT'] = 'SUCCESS';
        $returnArray['AUTH_URL'] = $redirectUrl;
        $returnArray['RAWREQUEST'] = json_encode(array_filter($requestData));
        return $returnArray;
    }
     
    /**
     * Use this call to retrieve user profile attributes.
     *
     * @param Array $requestData
     * @return Array|Object
     */
    public function GetUserInfo($requestData){
        try {
            $tokenInfo = new OpenIdTokeninfo();
            $tokenInfo = $tokenInfo->createFromRefreshToken(array('refresh_token' => $requestData['refreshToken']), $this->_api_context);
            $params = array('access_token' => $tokenInfo->getAccessToken());
            $userInfo = OpenIdUserinfo::getUserinfo($params, $this->_api_context);  
            $returnArray['RESULT'] = 'SUCCESS';
            $returnArray['USER_INFO'] = $userInfo->toArray();
            $returnArray['RAWREQUEST'] = json_encode($params);
            $returnArray['RAWRESPONSE'] = $userInfo->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $ex;   
        }
    }

    /**
     * Creates an Access Token from an Authorization Code.
     * @param $code
     * @return Array
     */
    public function GetUserConsentRedirect($code){
        try {
            // Obtain Authorization Code from Code, Client ID and Client Secret
            $accessToken = OpenIdTokeninfo::createFromAuthorizationCode(array('code' => $code), null, null, $this->_api_context);
            $returnArray['RESULT'] = 'SUCCESS';
            $returnArray['AccessToken'] = $accessToken->toArray();
            return $returnArray;
        } catch (PayPalConnectionException $ex) {
            echo "<pre>";
            print_r($ex);
            exit;
        }
    }
}
?>