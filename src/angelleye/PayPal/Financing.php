<?php namespace angelleye\PayPal;
/**
 *	An open source PHP library written to easily work with PayPal's Financing API
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
 * @version			v2.0.2.2
 * @filesource
*/

/**
 * PayPal Financing API Wrapper
 *
 * This is a wrapper to the PayPal Financing API.  
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */

class Financing extends PayPal
{
	var $AccessKey = '';
	var $ClientSecret = '';
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	mixed[]	$DataArray	Array of request parameters.
	 * @return	void
	 */
	function __construct($DataArray)
	{
		parent::__construct($DataArray);
		
		$this->AccessKey = isset($DataArray['AccessKey']) ? $DataArray['AccessKey'] : '';
		$this->ClientSecret = isset($DataArray['ClientSecret']) && $DataArray['ClientSecret'] != '' ? $DataArray['ClientSecret'] : '';
		
		if($this->Sandbox)
		{
			$this->EndPointURL = isset($DataArray['EndPointURL']) && $DataArray['EndPointURL'] != '' ? $DataArray['EndPointURL'] : 'https://api.financing-mint.paypal.com/finapi/v1/publishers/';
		}
		else
		{
			$this->EndPointURL = isset($DataArray['EndPointURL']) && $DataArray['EndPointURL'] != '' ? $DataArray['EndPointURL'] : 'https://api.financing.paypal.com/finapi/v1/publishers/';
		}
	}
	
	/**
	 * Builds all HTTP headers required for the API call.
	 *
	 * @access	public
	 * @param	boolean	$PrintHeaders	Option to print headers on screen or not (true/false)
	 * @return	string	$headers		Returns the HTTP headers as a string.
	 */
	function BuildHeaders($PrintHeaders = false)
	{
		$timestamp = intval(round(microtime(true) * 1000));
		$token = sha1($this->ClientSecret.$timestamp);

		$headers = array(
			"AUTHORIZATION: FPA ".$this->AccessKey.":".$token.":".$timestamp,
			"CONTENT-TYPE: application/json", 
			"ACCEPT: application/json"
		);
		
		if($PrintHeaders)
		{
			echo '<pre />';
			print_r($headers);
		}
		
		return $headers;
	}
	 
	/**
	 * Sends the API request to PayPal using CURL.
	 *
	 * @access	public
	 * @param	string	$Request		Raw API request string.
	 * @param	string	$APIName		The name of the API which you are calling.
	 * @param	string	$APIOperation	The method of the API which you are calling.
     * @param   string  $PrintHeaders   The option to print headers or not.
	 * @return	string	$Response		Returns the raw HTTP response from PayPal.
	 */
	function CURLRequest($Request = "", $APIName = "", $APIOperation = "", $PrintHeaders = false)
	{
		$curl = curl_init();
				// curl_setopt($curl, CURLOPT_HEADER,TRUE);
				curl_setopt($curl, CURLOPT_VERBOSE, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_URL, $this->EndPointURL);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $Request);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $this->BuildHeaders($this->PrintHeaders));
		
		$Response = curl_exec($curl);		
		curl_close($curl);
		return $Response;	
	}
	
	/**
	 * Parses all errors returned from PayPal
	 *
	 * @access	public
	 * @param	mixed[]	$JSONResponse	Raw JSON string to pull errors from.
	 * @return	mixed[]	$Errors			Returns an array result of errors pulled from the JSON string.
	 */
	function GetErrors($JSONResponse)
	{
		$JSONResponse = json_decode($JSONResponse);
		$Errors = isset($JSONResponse->errors) ? $JSONResponse->errors : array();
		
		return $Errors;
	}
	
	/**
	 * Enrolls a user in the financing banner system.
	 *
	 * @access public
	 * @param mixed[]	$DataArray		Array of request parameters.
	 * @return mixed[]	$ResultArray	Returns an array of the response parameters.
	 */
	function BannerEnrollment($DataArray)
	{
		$PayPalRequest['bnCode'] = $this->APIButtonSource;
		
		if(isset($DataArray['PayerID']))
		{
			$PayPalRequest['payerId'] = $DataArray['PayerID'];	
		}
		
		if(isset($DataArray['SellerName']))
		{
			$PayPalRequest['sellerName'] = $DataArray['SellerName'];	
		}
		
		if(isset($DataArray['EmailAddress']))
		{
			$PayPalRequest['emailAddress'] = $DataArray['EmailAddress'];	
		}
		
		$JSONRequest = json_encode($PayPalRequest);		
		$JSONResponse = $this->CURLRequest($JSONRequest,"","");
		$ResponseArray = json_decode($JSONResponse);
		
		$Errors = $this->GetErrors($JSONResponse);
		$PayerID = isset($ResponseArray->payerId) ? $ResponseArray->payerId : '';
		$PublisherID = isset($ResponseArray->publisherId) ? $ResponseArray->publisherId : '';
		
		
		$ResultArray = array(
			'RawRequest' => $JSONRequest, 
			'RawResponse' => $JSONResponse, 
			'PayerID' => $PayerID, 
			'PublisherID' => $PublisherID, 
			'Errors' => $Errors
		);
		
		return $ResultArray;
	}
}