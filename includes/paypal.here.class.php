<?php
/**
 * 	Angell EYE PayPal Here Class
 *	An open source PHP library written to easily work with PayPal's API's
 *
 *  Copyright © 2012  Andrew K. Angell
 *	Email:  andrew@angelleye.com
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
 * @package			Angell_EYE_PayPal_Here_Class_Library
 * @author			Andrew K. Angell
 * @copyright       Copyright © 2012 Angell EYE, LLC
 * @link			http://www.angelleye.com
 * @since			Version 1.5
 * @updated			10.31.2012
 * @filesource
 */

class PayPal_Here extends PayPal
{
	var $AccessToken = '';
	
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	config preferences
	 * @return	void
	 */
	function __construct($DataArray)
	{
		parent::__construct($DataArray);
		
		if($this->Sandbox)
		{	
			// Sandbox Credentials
			$this->AccessToken = isset($DataArray['AccessToken']) ? $DataArray['AccessToken'] : '';
			$this->EndPointURL = isset($DataArray['EndPointURL']) && $DataArray['EndPointURL'] != '' ? $DataArray['EndPointURL'] : 'https://sandbox.paypal.com/webapps/hereapi/merchant/v1/';
		}
		else
		{
			// Live Credentials
			$this->AccessToken = isset($DataArray['AccessToken']) ? $DataArray['AccessToken'] : '';
			$this->EndPointURL = isset($DataArray['EndPointURL']) && $DataArray['EndPointURL'] != ''  ? $DataArray['EndPointURL'] : 'https://www.paypal.com/webapps/hereapi/merchant/v1/';
		}
	}
	
	/**
	 * Build all HTTP headers required for the API call.
	 *
	 * @access	public
	 * @param	boolean	$PrintHeaders - Whether to print headers on screen or not (true/false)
	 * @return	array $headers
	 */
	function BuildHeaders($PrintHeaders)
	{
		$headers = array(
						'Authorization: Bearer '.$this->AccessToken
						);
		
		if($PrintHeaders)
		{
			echo '<pre />';
			print_r($headers);
		}
		
		return $headers;
	}
	
	/**
	 * Send the request to PayPal using CURL
	 *
	 * @access	public
	 * @param	string $Request
	 * @param   string $APIName
	 * @param   string $APIOperation
	 * @return	string
	 */
	function CURLRequest($Request = "", $APIName = "", $APIOperation = "")
	{
		$curl = curl_init();
				curl_setopt($curl, CURLOPT_VERBOSE, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_URL, $this->EndPointURL);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $Request);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $this->BuildHeaders(false));
								
		if($this -> APIMode == 'Certificate')
		{
			curl_setopt($curl, CURLOPT_SSLCERT, $this -> PathToCertKeyPEM);
		}
		
		$Response = curl_exec($curl);		
		curl_close($curl);
		return $Response;
	}
	
	/**
	 * Redirect user to PayPal for OAuth authentication.
	 *
	 * @access	public
	 * @param	array	call config data
	 * @return	array
	 */
	function OAuth_Redirect($DataArray)
	{
		$ResponseType = isset($DataArray['ResponseType']) ? $DataArray['ResponseType'] : 'code';
		$RedirectURL = isset($DataArray['RedirectURL']) ? $DataArray['RedirectURL'] : '';
		$ClientID = isset($DataArray['ClientID']) ? $DataArray['ClientID'] : '';
		
		$OAuthURL = 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/authorize?scope=http://uri.paypal.com/services/paypalhere';
		$OAuthURL .= $ResponseType != '' ? '&response_type='.$ResponseType : '';
		$OAuthURL .= $RedirectURL != '' ? '&redirect_uri='.$RedirectURL : '';
		$OAuthURL .= $ClientID != '' ? '&client_id='.$ClientID : '';
		
		header('Location: '.$OAuthURL);
		exit();
	}
	
	/**
	 * Get access and refresh tokens.
	 *
	 * @access	public
	 * @param	array	call config data
	 * @return	array
	 */
	function OAuth_GetTokens($DataArray)
	{
		/*
			$PayPalRequestData = array(
									'GrantType' => '',		// Value authorization_code to obtain a new token.  Value refresh_token to refresh an expired token. 
									'Code' => ''			// Access code.
									);
		*/
		
		$GrantType = isset($DataArray['GrantType']) ? $DataArray['GrantType'] : '';
		$Code = isset($DataArray['Code']) ? $DataArray['Code'] : '';
		
		$this->EndPointURL = $this->Sandbox ? 'https://sandbox.paypal.com/webapps/auth/protocol/openidconnect/v1/tokenservice' : 'https://www.paypal.com/webapps/auth/protocol/openidconnect/v1/tokenservice';
		
		$NVPRequest = 'grant_type='.$GrantType.'&code='.$Code;
		$NVPResponse = $this->CURLRequest($NVPRequest);
		
		$NVPResponseArray = json_decode($NVPResponse);
		
		$ResponseDataArray = array(
								   'TokenType' => isset($NVPResponseArray['token_type']) ? $NVPResponseArray['token_type'] : '', 
								   'RefreshToken' => isset($NVPResponseArray['refresh_token']) ? $NVPResponseArray['refresh_token'] : '', 
								   'AccessToken' => isset($NVPResponseArray['access_token']) ? $NVPResponseArray['access_token'] : '', 
								   'ExpiresIn' => isset($NVPResponseArray['expires_in']) ? $NVPResponseArray['expires_in'] : ''
								   );
		
		return $ResponseDataArray;
		
	}
	
}