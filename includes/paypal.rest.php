<?php
/**
 * 	Angell EYE PayPal Adaptive Payments Class
 *	An open source PHP library written to easily work with PayPal's API's
 *
 *  Copyright © 2013  Andrew K. Angell
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
 * @package			Angell_EYE_PayPal_REST_Class_Library
 * @author			Andrew K. Angell
 * @copyright       Copyright © 2013 Angell EYE, LLC
 * @link			http://www.angelleye.com
 * @since			Version 1.51
 * @updated			04.27.2013
 * @filesource
 */
 
class PayPal_REST extends PayPal
{
	var $ClientID = '';
	var $ClientSecret = '';
	var $AccessToken = '';
	var $AccessTokenType = '';
	
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
		
		$this->EndPointURL = $this->Sandbox ? 'https://api.sandbox.paypal.com/v1/' : 'https://api.paypal.com/v1/';
		$this->ClientID = isset($DataArray['ClientID']) ? $DataArray['ClientID'] : '';
		$this->ClientSecret = isset($DataArray['Secret']) ? $DataArray['Secret'] : '';
		
		$NVPString = 'grant_type=client_credentials';
		$PayPalResult = $this->CURLRequest($NVPString,'oauth2','token');
		
		$this->AccessTokenType = $PayPalResult['Body']->token_type;
		$this->AccessToken = $PayPalResult['Body']->access_token;
	}
	
	/**
	 * Send the API request to PayPal using CURL
	 *
	 * @access	public
	 * @param	string $Request
	 * @param   string $APIName
	 * @param   string $APIOperation
	 * @return	string
	 */
	function CURLRequest($Request = "", $APIName = "", $APIOperation = "")
	{	
		echo '<pre />';
		echo $this->EndPointURL.$APIName.'/'.$APIOperation.'<br /><br />';
		
		$curl = curl_init($this->EndPointURL.$APIName.'/'.$APIOperation);
				
		if($APIName == 'oauth2')
		{
			// Sending request to obtain bearer token
			$headers = array('Accept:application/json', 'Accept-Language:en_US');
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($curl, CURLOPT_USERPWD, $this->ClientID.':'.$this->ClientSecret);
			curl_setopt($curl, CURLOPT_SSLVERSION, 3);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		}
		else
		{
			// Sending request with bearer token for actual API calls
			$headers = array('Content-Type:application/json', 'Authorization:'.$this->AccessTokenType.' '.$this->AccessToken);
		}
		
		echo '<pre />';
		print_r($headers);
		echo '<br />';
		
		$options = array(
						CURLOPT_HEADER => TRUE, 
						CURLINFO_HEADER_OUT => TRUE, 
						CURLOPT_HTTPHEADER => $headers, 
						CURLOPT_RETURNTRANSFER => TRUE, 
						CURLOPT_VERBOSE => TRUE, 
						CURLOPT_TIMEOUT => 10, 
						CURLOPT_POSTFIELDS => $Request, 
						CURLOPT_CUSTOMREQUEST => 'POST'
						);
		
		curl_setopt_array($curl, $options);
		
		$Response = curl_exec($curl);
		echo $Request.'<br /><br />'.$Response.'<br /><br />';
		$Header = substr($Response,0,curl_getinfo($curl,CURLINFO_HEADER_SIZE));	
		$Body = json_decode(substr($Response, curl_getinfo($curl,CURLINFO_HEADER_SIZE)));
		curl_close($curl);
		
		return array('Header'=>$Header,'Body'=>$Body);
	}
	
	function CreatePayment($DataArray)
	{	
		$PayPalRequestArray = array();
		
		// General Info
		$General = isset($DataArray['General']) ? $DataArray['General'] : array();
		
		if(count($General) > 0)
		{
			$Intent = isset($General['intent']) ? $General['intent'] : '';
			$PayPalRequestArray['intent'] = $Intent;	
		}
		
		// Payer Info
		$Payer = isset($DataArray['Payer']) ? $DataArray['Payer'] : array();
		if(count($Payer) > 0)
		{			
			if(isset($Payer['payment_method']) && $Payer['payment_method'] != '')
			{
				$PayPalRequestArray['payer']['payment_method'] = $Payer['payment_method'];	
			}
			
			$CreditCard = isset($DataArray['CreditCard']) ? $DataArray['CreditCard'] : array();
			if(count($CreditCard) > 0)
			{
				if(isset($CreditCard['id']) && $CreditCard['id'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['id'] = $CreditCard['id'];	
				}
				
				if(isset($CreditCard['number']) && $CreditCard['number'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['number'] = $CreditCard['number'];	
				}
				
				if(isset($CreditCard['type']) && $CreditCard['type'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['type'] = $CreditCard['type'];	
				}
				
				if(isset($CreditCard['expire_month']) && $CreditCard['expire_month'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['expire_month'] = $CreditCard['expire_month'];	
				}
				
				if(isset($CreditCard['expire_year']) && $CreditCard['expire_year'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['expire_year'] = $CreditCard['expire_year'];	
				}
				
				if(isset($CreditCard['cvv2']) && $CreditCard['cvv2'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['cvv2'] = $CreditCard['cvv2'];	
				}
				
				if(isset($CreditCard['first_name']) && $CreditCard['first_name'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['first_name'] = $CreditCard['first_name'];	
				}
				
				if(isset($CreditCard['last_name']) && $CreditCard['last_name'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['last_name'] = $CreditCard['last_name'];	
				}
			}
			
			$BillingAddress = isset($DataArray['BillingAddress']) ? $DataArray['BillingAddress'] : array();
			if(count($BillingAddress) > 0)
			{
				if(isset($BillingAddress['type']) && $BillingAddress['type'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['billing_address']['type'] = $BillingAddress['type'];	
				}	
				
				if(isset($BillingAddress['line1']) && $BillingAddress['line1'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['billing_address']['line1'] = $BillingAddress['line1'];	
				}	
				
				if(isset($BillingAddress['line2']) && $BillingAddress['line2'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['billing_address']['line2'] = $BillingAddress['line2'];	
				}	
				
				if(isset($BillingAddress['city']) && $BillingAddress['city'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['billing_address']['city'] = $BillingAddress['city'];	
				}
				
				if(isset($BillingAddress['country_code']) && $BillingAddress['country_code'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['billing_address']['country_code'] = $BillingAddress['country_code'];	
				}	
				
				if(isset($BillingAddress['postal_code']) && $BillingAddress['postal_code'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['billing_address']['postal_code'] = $BillingAddress['postal_code'];	
				}
				
				if(isset($BillingAddress['state']) && $BillingAddress['state'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['billing_address']['state'] = $BillingAddress['state'];	
				}
				
				if(isset($BillingAddress['phone']) && $BillingAddress['phone'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card']['billing_address']['phone'] = $BillingAddress['phone'];	
				}
			}
			
			$CreditCardToken = isset($DataArray['CreditCardToken']) ? $DataArray['CreditCardToken'] : array();
			if(count($CreditCardToken) > 0)
			{
				if(isset($CreditCardToken['credit_card_id']) && $CreditCardToken['credit_card_id'] != '')
				{
					$PayPalRequestArray['payer']['funding_instruments']['credit_card_token']['credit_card_id'] = $CreditCardToken['credit_card_id'];	
				}	
			}
			
			$PayerInfo = isset($DataArray['PayerInfo']) ? $DataArray['PayerInfo'] : array();
			if(count($PayerInfo) > 0)
			{
				if(isset($PayerInfo['email']) && $PayerInfo['email'] != '')
				{
					$PayPalRequestArray['payer']['payer_info']['email'] = $PayerInfo['email'];	
				}
				
				if(isset($PayerInfo['phone']) && $PayerInfo['phone'] != '')
				{
					$PayPalRequestArray['payer']['payer_info']['phone'] = $PayerInfo['phone'];	
				}
				
				$ShippingAddress = isset($DataArray['ShippingAddress']) ? $DataArray['ShippingAddress'] : array();
				if(count($ShippingAddress) > 0)
				{
					if(isset($ShippingAddress['recipient_name']) && $ShippingAddress['recipient_name'] != '')
					{
						$PayPalRequestArray['payer']['payer_info']['shipping_address']['recipient_name'] = $ShippingAddress['recipient_name'];	
					}
					
					if(isset($ShippingAddress['type']) && $ShippingAddress['type'] != '')
					{
						$PayPalRequestArray['payer']['payer_info']['shipping_address']['type'] = $ShippingAddress['type'];	
					}	
					
					if(isset($ShippingAddress['line1']) && $ShippingAddress['line1'] != '')
					{
						$PayPalRequestArray['payer']['payer_info']['shipping_address']['line1'] = $ShippingAddress['line1'];	
					}
					
					if(isset($ShippingAddress['line2']) && $ShippingAddress['line2'] != '')
					{
						$PayPalRequestArray['payer']['payer_info']['shipping_address']['line2'] = $ShippingAddress['line2'];	
					}
					
					if(isset($ShippingAddress['city']) && $ShippingAddress['city'] != '')
					{
						$PayPalRequestArray['payer']['payer_info']['shipping_address']['city'] = $ShippingAddress['city'];	
					}
					
					if(isset($ShippingAddress['country_code']) && $ShippingAddress['country_code'] != '')
					{
						$PayPalRequestArray['payer']['payer_info']['shipping_address']['country_code'] = $ShippingAddress['country_code'];	
					}
					
					if(isset($ShippingAddress['postal_code']) && $ShippingAddress['postal_code'] != '')
					{
						$PayPalRequestArray['payer']['payer_info']['shipping_address']['postal_code'] = $ShippingAddress['postal_code'];	
					}
					
					if(isset($ShippingAddress['state']) && $ShippingAddress['state'] != '')
					{
						$PayPalRequestArray['payer']['payer_info']['shipping_address']['state'] = $ShippingAddress['state'];	
					}
					
					if(isset($ShippingAddress['phone']) && $ShippingAddress['phone'] != '')
					{
						$PayPalRequestArray['payer']['payer_info']['shipping_address']['phone'] = $ShippingAddress['phone'];	
					}
				}
			}
		}
		
		// Transactions Info
		$Transactions = isset($DataArray['Transactions']) ? $DataArray['Transactions'] : array();
		if(count($Transactions) > 0)
		{
			$i = 0;
			foreach($Transactions as $Transaction)
			{
				if(isset($Transaction['currency']) && $Transaction['currency'] != '')
				{
					$PayPalRequestArray['transactions'][$i]['amount']['currency'] = $Transaction['currency'];
				}
				
				if(isset($Transaction['total']) && $Transaction['total'] != '')
				{
					$PayPalRequestArray['transactions'][$i]['amount']['total'] = $Transaction['total'];
				}
				
				if(isset($Transaction['shipping']) && $Transaction['shipping'] != '')
				{
					$PayPalRequestArray['transactions'][$i]['amount']['details']['shipping'] = $Transaction['shipping'];
				}
				
				if(isset($Transaction['subtotal']) && $Transaction['subtotal'] != '')
				{
					$PayPalRequestArray['transactions'][$i]['amount']['details']['subtotal'] = $Transaction['subtotal'];
				}
				
				if(isset($Transaction['tax']) && $Transaction['tax'] != '')
				{
					$PayPalRequestArray['transactions'][$i]['amount']['details']['tax'] = $Transaction['tax'];
				}
				
				if(isset($Transaction['description']) && $Transaction['description'] != '')
				{
					$PayPalRequestArray['transactions'][$i]['description'] = $Transaction['description'];
				}
				
				$TransactionItems = isset($Transaction['Items']) ? $Transaction['Items'] : array();
				if(count($TransactionItems) > 0)
				{
					$n = 0;
					foreach($TransactionItems as $TransactionItem)
					{
						if(isset($TransactionItem['quantity']) && $TransactionItem['quantity'] != '')
						{
							$PayPalRequestArray['transactions']['item_list']['items'][$n]['quantity'] = $TransactionItem['quantity'];	
						}
						
						if(isset($TransactionItem['name']) && $TransactionItem['name'] != '')
						{
							$PayPalRequestArray['transactions']['item_list']['items'][$n]['name'] = $TransactionItem['name'];	
						}
						
						if(isset($TransactionItem['price']) && $TransactionItem['price'] != '')
						{
							$PayPalRequestArray['transactions']['item_list']['items'][$n]['price'] = $TransactionItem['price'];	
						}
						
						if(isset($TransactionItem['currency']) && $TransactionItem['currency'] != '')
						{
							$PayPalRequestArray['transactions']['item_list']['items'][$n]['currency'] = $TransactionItem['currency'];	
						}
						
						if(isset($TransactionItem['sku']) && $TransactionItem['sku'] != '')
						{
							$PayPalRequestArray['transactions']['item_list']['items'][$n]['sku'] = $TransactionItem['sku'];	
						}
						
						$TransactionItemShippingAddress = isset($TransactionItem['shipping_address']) ? $TransactionItem['shipping_address'] : array();
						if(count($TransactionItemShippingAddress) > 0)
						{
							if(isset($TransactionItemShippingAddress['recipient_name']) && $TransactionItemShippingAddress['recipient_name'] != '')
							{
								$PayPalRequestArray['transactions']['item_list']['shipping_address']['recipient_name'] = $TransactionItemShippingAddress['recipient_name'];	
							}
							
							if(isset($TransactionItemShippingAddress['type']) && $TransactionItemShippingAddress['type'] != '')
							{
								$PayPalRequestArray['transactions']['item_list']['shipping_address']['type'] = $TransactionItemShippingAddress['type'];	
							}
							
							if(isset($TransactionItemShippingAddress['line1']) && $TransactionItemShippingAddress['line1'] != '')
							{
								$PayPalRequestArray['transactions']['item_list']['shipping_address']['line1'] = $TransactionItemShippingAddress['line1'];	
							}	
							
							if(isset($TransactionItemShippingAddress['line2']) && $TransactionItemShippingAddress['line2'] != '')
							{
								$PayPalRequestArray['transactions']['item_list']['shipping_address']['line2'] = $TransactionItemShippingAddress['line2'];	
							}
							
							if(isset($TransactionItemShippingAddress['city']) && $TransactionItemShippingAddress['city'] != '')
							{
								$PayPalRequestArray['transactions']['item_list']['shipping_address']['city'] = $TransactionItemShippingAddress['city'];	
							}
							
							if(isset($TransactionItemShippingAddress['country_code']) && $TransactionItemShippingAddress['country_code'] != '')
							{
								$PayPalRequestArray['transactions']['item_list']['shipping_address']['country_code'] = $TransactionItemShippingAddress['country_code'];	
							}
							
							if(isset($TransactionItemShippingAddress['postal_code']) && $TransactionItemShippingAddress['postal_code'] != '')
							{
								$PayPalRequestArray['transactions']['item_list']['shipping_address']['postal_code'] = $TransactionItemShippingAddress['postal_code'];	
							}
							
							if(isset($TransactionItemShippingAddress['state']) && $TransactionItemShippingAddress['state'] != '')
							{
								$PayPalRequestArray['transactions']['item_list']['shipping_address']['state'] = $TransactionItemShippingAddress['state'];	
							}
							
							if(isset($TransactionItemShippingAddress['phone']) && $TransactionItemShippingAddress['phone'] != '')
							{
								$PayPalRequestArray['transactions']['item_list']['shipping_address']['phone'] = $TransactionItemShippingAddress['phone'];	
							}
						}
						
						$n++;
					}
				}
				
				$i++;
			}	
		}
		
		// RedirectURLs Info
		$RedirectURLs = isset($DataArray['RedirectURLs']) ? $DataArray['RedirectURLs'] : array();
		if(count($RedirectURLs) > 0)
		{
			if(isset($RedirectURLs['return_url']) && $RedirectURLs['return_url'] != '')
			{
				$PayPalRequestArray['redirect_urls']['return_url'] = $RedirectURLs['return_url'];	
			}
			
			if(isset($RedirectURLs['cancel_url']) && $RedirectURLs['cancel_url'] != '')
			{
				$PayPalRequestArray['redirect_urls']['cancel_url'] = $RedirectURLs['cancel_url'];	
			}	
		}
		
		$PayPalRequest = json_encode($PayPalRequestArray);
		
		$Response = $this->CURLRequest($PayPalRequest,'payments','payment');
				
		return $Response;
	}
}