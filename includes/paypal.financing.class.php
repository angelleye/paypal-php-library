<?php
class PayPal_Financing extends PayPal
{
	var $AccessKey = '';
	var $ClientSecret = '';
	
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
	 * Build all HTTP headers required for the API call.
	 *
	 * @access	public
	 * @param	boolean	$PrintHeaders - Whether to print headers on screen or not (true/false)
	 * @return	array $headers
	 */
	function BuildHeaders($PrintHeaders)
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
	 * Send the API request to PayPal using CURL
	 *
	 * @access	public
	 * @param	string	JSON string
	 * @return	string
	 */
	function CURLRequest($Request = "", $APIName = "", $APIOperation = "")
	{
		$curl = curl_init();
				// curl_setopt($curl, CURLOPT_HEADER,TRUE);
				curl_setopt($curl, CURLOPT_VERBOSE, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_URL, $this->EndPointURL);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $Request);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $this->BuildHeaders(FALSE));
		
		$Response = curl_exec($curl);		
		curl_close($curl);
		return $Response;	
	}
	
	/**
	 * Get all errors returned from PayPal
	 *
	 * @access	public
	 * @param	array	PayPal NVP response
	 * @return	array
	 */
	function GetErrors($JSONResponse)
	{
		$JSONResponse = json_decode($JSONResponse);
		$Errors = isset($JSONResponse->errors) ? $JSONResponse->errors : array();
		
		return $Errors;
	}
	
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