<?php namespace angelleye\PayPal;
/**
 *	An open source PHP library written to easily work with PayPal's PayFlow API
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
 * @filesource
*/

/**
 * PayPal PayFlow Gateway Class
 *
 * This class is a wrapper for the PayFlow gateway.  
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */

class PayFlow extends PayPal 
{	 
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	mixed[]	$DataArray	Array structure providing config data
	 * @return	void
	 */
	function __construct($DataArray)
	{
		parent::__construct($DataArray);
		
		$this->APIVendor = isset($DataArray['APIVendor']) ? $DataArray['APIVendor'] : '';
		$this->APIPartner = isset($DataArray['APIPartner']) ? $DataArray['APIPartner'] : '';
		$this->Verbosity = isset($DataArray['Verbosity']) ? $DataArray['Verbosity'] : 'HIGH';
		
		if($this->Sandbox)
		{
			$this->APIEndPoint = 'https://pilot-payflowpro.paypal.com';
		}
		else
		{
			$this->APIEndPoint = 'https://payflowpro.paypal.com';
		}
		
		$this->NVPCredentials = 'BUTTONSOURCE['.strlen($this->APIButtonSource).']='.$this->APIButtonSource.'&VERBOSITY['.strlen($this->Verbosity).']='.$this->Verbosity.'&USER['.strlen($this->APIUsername).']='.$this->APIUsername.'&VENDOR['.strlen($this->APIVendor).']='.$this->APIVendor.'&PARTNER['.strlen($this->APIPartner).']='.$this->APIPartner.'&PWD['.strlen($this->APIPassword).']='.$this->APIPassword;
		
		$this->TransactionStateCodes = array(
				'1' => 'Error',
				'6' => 'Settlement Pending',
				'7' => 'Settlement in Progress',
				'8' => 'Settlement Completed Successfully',
				'11' => 'Settlement Failed',
				'14' => 'Settlement Incomplete'
		);
	}	
	
	/*
	 * GetTransactionStateCodeMessage
	 * 
	 * @access public
	 * @param	int		$Code	Transaction state code to obtain the full message for.
	 * @return	string	Returns the full message for the supplied code.
	 */
	function GetTransactionStateCodeMessage($Code)
	{
		return $this -> TransactionStateCodes[$Code];
	}
	
	/*
	 * CURLRequest
	 * 
	 * @access public
	 * @param string Request
	 * @return string
	 */
	 
	/**
	 * Send the API request to PayFlow using CURL.
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
	
		$unique_id = date('ymd-His').rand(1000,9999);
	
		$headers[] = "Content-Type: text/namevalue"; //or text/xml if using XMLPay.
		$headers[] = "Content-Length : " . strlen ($Request);  // Length of data to be passed
		$headers[] = "X-VPS-Timeout: 45";
		$headers[] = "X-VPS-Request-ID:" . $unique_id;

        if($this->PrintHeaders)
        {
            echo '<pre />';
            print_r($headers);
        }
	
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_HEADER, 1);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 90);
		curl_setopt($curl, CURLOPT_URL, $this->APIEndPoint);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $Request);
	
		// Try to submit the transaction up to 3 times with 5 second delay.  This can be used
		// in case of network issues.  The idea here is since you are posting via HTTPS there
		// could be general network issues, so try a few times before you tell customer there
		// is an issue.
		$i=1;
		while ($i++ <= 3)
		{
			$Response = curl_exec($curl);
			$headers = curl_getinfo($curl);
				
			if ($headers['http_code'] != 200) {
				sleep(5);  // Let's wait 5 seconds to see if its a temporary network issue.
			}
			else if ($headers['http_code'] == 200)
			{
				// we got a good response, drop out of loop.
				break;
			}
		}
	
		curl_close($curl);
	
		return $Response;
	}
	
	
	/**
	 * Converts an NVP string to an array with URL decoded values
	 *
	 * @access	public
	 * @param	string	$NVPString	Raw NVP string to be converted to an array.
	 * @return	mixed[]	$proArray	Returns the NVP string as an array.
	 */
	function NVPToArray($NVPString)
	{
		$proArray = array();
		parse_str($NVPString,$proArray);
		return $proArray;
	}
	
	/**
	 * Processes the transaction on the PayFlow gateway.
	 * 
	 * @access public
	 * @param	mixed[]	$DataArray			Array of request parameters.
	 * @return	mixed[]	$NVPResponseArray	Returns the PayFlow response data as an array.
	 */
	function ProcessTransaction($DataArray)
	{
		$NVPRequest = $this->NVPCredentials;
		
		foreach($DataArray as $DataArrayVar => $DataArrayVal)
		{
			if($DataArrayVal != '')
			{
				$NVPRequest .= '&'.strtoupper($DataArrayVar).'['.strlen($DataArrayVal).']='.$DataArrayVal;
			}
		}
		
		$NVPResponse = $this->CURLRequest($NVPRequest);
		$NVPResponse = strstr($NVPResponse,"RESULT");
		$NVPResponseArray = $this->NVPToArray($NVPResponse);

        $this->Logger($this->LogPath, 'PayFlowRequest', $NVPRequest);
        $this->Logger($this->LogPath, 'PayFlowResponse', $NVPResponse);
		
		$NVPResponseArray['RAWREQUEST'] = $NVPRequest;
		$NVPResponseArray['RAWRESPONSE'] = $NVPResponse;
		
		return $NVPResponseArray;
	}
}