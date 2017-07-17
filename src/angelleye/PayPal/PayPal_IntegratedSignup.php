<?php

namespace angelleye\PayPal;

/**
 *  An open source PHP library written to easily work with PayPal's Adaptive Payments API
 * 	
 *  Email:  service@angelleye.com
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
 * @filesource
 */

/**
 * PayPal Adaptive Payments Class
 *
 * This class houses all of the Adaptive Payments specific API's.  
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
use DOMDocument;

class PayPal_IntegratedSignup extends PayPal {

    private $_auth_string;
    private $_bearer_string;
    var $Sandbox = '';
    var $PathToCertKeyPEM = '';
    var $SSL = '';
    var $PrintHeaders = '';
    var $LogResults = '';
    var $LogPath = '';

    public function __construct($configArray) {
        // Append your secret to your client ID, separated by a colon (“:”). Base64-encode the resulting string.
        $this->_auth_string = base64_encode($configArray['ClientID'] . ':' . $configArray['ClientSecret']);
        if (isset($configArray['Sandbox'])) {
            $this->Sandbox = $configArray['Sandbox'];
        } else {
            $this->Sandbox = true;
        }

        $this->PrintHeaders = isset($configArray['PrintHeaders']) ? $configArray['PrintHeaders'] : false;
        $this->LogResults = isset($configArray['LogResults']) ? $configArray['LogResults'] : false;
        $this->LogPath = isset($configArray['LogPath']) ? $configArray['LogPath'] : '/logs/';

        if ($this->Sandbox) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            $this->EndPointURL = 'https://api.sandbox.paypal.com/v1/';
        } else {
            $this->EndPointURL = 'https://api.paypal.com/v1/';
        }
    }
        
    public function IntegratedSignup($requestData) {        
        /*          
         * Authentication
         * Before using any of the PayPal REST APIs, you must first authenticate yourself and
         * obtain an access token. This call uses the client token and secret assigned to you by
         * PayPal; all other calls will use the access token obtained here.
         */
        $Request = "grant_type=client_credentials";
        $AuthResponseJson = $this->CURLRequest($Request,'oauth2','token', $this->PrintHeaders);
        $AuthResponseArray =json_decode($AuthResponseJson, true);
        /* If token is is received then we can process for ISP */
        if(isset($AuthResponseArray['token_type']) && isset($AuthResponseArray['access_token'])){
            $TrimmedArray = $this->array_trim($requestData);
            $RequestPayload = json_encode($TrimmedArray, 0 | 64);
            $this->_bearer_string = $AuthResponseArray['access_token'];
            $ConnectPayPalJson = $this->CURLRequest($RequestPayload, 'customer', 'partner-referrals', $this->PrintHeaders);
            $ConnectPayPalArray = json_decode($ConnectPayPalJson, true);
            if(isset($ConnectPayPalArray['links'])){
                return $ConnectPayPalArray;
            }
            else{
                return $ConnectPayPalArray;
            }
        }
        else{
            return $AuthResponseArray;
        }
    }

    function BuildHeaders($PrintHeaders,$API) {
        $headers = array();
        if($API == 'oauth2/token'){
            $headers = array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $this->_auth_string,
            );
        }
        if($API == 'customer/partner-referrals'){
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->_bearer_string,
            );
        }        
        if ($PrintHeaders) {
            echo '<pre />';
            print_r($headers);
        }
        return $headers;
    }

    function CURLRequest($Request = "", $APIName = "", $APIOperation = "", $PrintHeaders = false) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, $this->Sandbox);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $Request);
        curl_setopt($curl, CURLOPT_URL, $this->EndPointURL . $APIName . '/' . $APIOperation);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->BuildHeaders($this->PrintHeaders,$APIName.'/'.$APIOperation));
        $result = curl_exec($curl);        
        /*
        * If a cURL error occurs, output it for review.
        */
       if($this->Sandbox)
       {
               if(curl_error($curl))
               {
                       echo curl_error($curl).'<br /><br />';	
               }
       }

       curl_close($curl);
       return $result;
    }
    
    public function array_trim($input) {
        return is_array($input) ? array_filter($input, 
            function (& $value) { return $value = $this->array_trim($value); }
        ) : $input;
    }

}
