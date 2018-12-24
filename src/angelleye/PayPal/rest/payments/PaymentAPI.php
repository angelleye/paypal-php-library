<?php

namespace angelleye\PayPal\rest\payments;

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

use PayPal\Api\Amount;
use PayPal\Api\Address;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Details;
use PayPal\Api\ExecutePayment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Order;
use PayPal\Api\Payer;
use PayPal\Api\Payee;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Refund;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Api\Transaction;
use \angelleye\PayPal\RestClass;

/**
 * PaymentAPI.
 * This class is responsible for Payment APIs & bridge class between the REST API class and Angelleye PayPal Library.
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
class PaymentAPI extends RestClass {

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
     * Creates a sale, an authorized payment to be captured later, or an order. 
     *
     * @param array $requestData
     * @return array|object
     */
    public function payment_create($requestData) {
        try {
            // ### PaymentCard
            // A resource representing a payment card that can be used to fund a payment.
            $card = new PaymentCard();
            if(isset($requestData['paymentCard'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['paymentCard']), $card);
            }
            $ba = $this->checkEmptyObject($requestData['billingAddress']);
            if(!empty($ba)){
                $this->setArrayToMethods(array("BillingAddress" => $ba), $card);
            }            
            
            // ### FundingInstrument
            // A resource representing a Payer's funding instrument.
            $fi = new FundingInstrument();
            if(!empty($this->checkEmptyObject((array)$card))){
                $fi->setPaymentCard($card);           
            }
                       
            // ### Payer
            // A resource representing a Payer that funds a payment
            $payer = new Payer();
            $payer->setPaymentMethod("credit_card");
            if(!empty($this->checkEmptyObject((array)$fi))){
                $payer->setFundingInstruments(array($fi));
            }
             
            // ### Itemized information
            // (Optional) Lets you specify item wise information
            $itemListArray = array();
            if(!empty($this->checkEmptyObject($requestData['orderItems']))){
                foreach ($requestData['orderItems'] as $value) {
                   $item = new Item();
                   $array = array_filter($value);
                   if (count($array) > 0) {
                       $this->setArrayToMethods($array, $item);
                       array_push($itemListArray, $item);
                   }
               }   
            }
            
            $itemList = new ItemList();
            if(!empty($this->checkEmptyObject($itemListArray))){
                $itemList->setItems($itemListArray);
            }            
            
            // ### Additional payment details
            // Use this optional field to set additional payment information such as tax, shipping charges etc.
            $details = new Details();
            if (isset($requestData['paymentDetails'])) {                
                $this->setArrayToMethods($this->checkEmptyObject($requestData['paymentDetails']), $details);
            }            
            // ### Amount
            // Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            if (isset($requestData['amount'])) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['amount']), $amount);
            }
            if (!empty($this->checkEmptyObject((array)$details))) {
                $amount->setDetails($details);
            }

            // ### Transaction
            // A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            if(!empty($this->checkEmptyObject((array)$amount))){
                $transaction->setAmount($amount);
            }
            
            if (!empty($this->checkEmptyObject((array)$itemList))) {
                $transaction->setItemList($itemList);
            }
            
            if (isset($requestData['transaction'])) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['transaction']), $transaction);
            }

            // ### Payment
            // A Payment Resource; create one using the above types and intent set to sale 'sale'
            $payment = new Payment();
            if(isset($requestData['ExperienceProfileId']) && !empty(trim($requestData['ExperienceProfileId']))){
                $payment->setExperienceProfileId($requestData['ExperienceProfileId']);
            }
            if(isset($requestData['NoteToPayer']) && !empty(trim($requestData['NoteToPayer']))){
                $payment->setNoteToPayer($requestData['NoteToPayer']);
            }
            
            $payment->setIntent(trim($requestData['intent']));
            if(!empty($this->checkEmptyObject((array)$payer))){
                $payment->setPayer($payer);
            }
            if(!empty($this->checkEmptyObject((array)$transaction))){
                $payment->setTransactions(array($transaction));
            }            
            // ### Create Payment
            // Create a payment by calling the payment->create() method with a valid ApiContext. The return object contains the state.
            $requestArray = clone $payment;
            $payment->create($this->_api_context);
            if ($requestData['intent'] == 'authorize') {
                $transactions = $payment->getTransactions();
                $relatedResources = $transactions[0]->getRelatedResources();
                $authorization = $relatedResources[0]->getAuthorization();
                $returnArray['RESULT'] = 'Success';
                $returnArray['AUTHORIZATION'] = $authorization->toArray();
                $returnArray['PAYMENT'] = $payment->toArray();
                $returnArray['RAWREQUEST']=$requestArray->toJSON();
                $returnArray['RAWRESPONSE']=$payment->toJSON();
                return $returnArray;
            } else {
                $returnArray['RESULT'] = 'Success';
                $returnArray['PAYMENT']=$payment->toArray();
                $returnArray['RAWREQUEST']=$requestArray->toJSON();
                $returnArray['RAWRESPONSE']=$payment->toJSON();
                return $returnArray;
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     *  Creates a sale, an authorized payment to be captured later, or an order. 
     *
     * @param array $requestData
     * @return array|object
     */
    public function create_payment_with_paypal($requestData) {

        try {
            // ### Payer
            // A resource representing a Payer that funds a payment
            // For paypal account payments, set payment method
            // to 'paypal'.
            $payer = new Payer();
            $payer->setPaymentMethod("paypal");

            // ### Itemized information
            // (Optional) Lets you specify item wise information
            $itemListArray = array();
            if (isset($requestData['orderItems'])) {                                        
                foreach ($this->checkEmptyObject($requestData['orderItems']) as $value) {
                    $item = new Item();
                    $array = array_filter($value);
                    if (count($array) > 0) {
                        $this->setArrayToMethods($array, $item);
                        array_push($itemListArray, $item);
                    }
                }
            }
            $itemList = new ItemList();
            if(!empty($itemListArray)){
                $itemList->setItems($itemListArray);
            }
            
            // ### Additional payment details
            // Use this optional field to set additional payment information such as tax, shipping charges etc.
            $details = new Details();
            if (isset($requestData['paymentDetails'])) {                
                $this->setArrayToMethods($this->checkEmptyObject($requestData['paymentDetails']), $details);
            }

            // ### Amount
            // Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            if (isset($requestData['amount'])) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['amount']), $amount);
            }
            
            $detailArray = $this->checkEmptyObject((array)$details); 
            if ( !empty($detailArray) ) {
                $amount->setDetails($details);
            }

            // ### Transaction
            // A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            if (!empty($this->checkEmptyObject((array)$amount))) {
                $transaction->setAmount($amount);
            }
            
            if (!empty($this->checkEmptyObject((array)$itemList))) {
                $transaction->setItemList($itemList);
            }
            if (isset($requestData['transaction'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['transaction']), $transaction);
            }
            if(isset($requestData['invoiceNumber']) && !empty(trim($requestData['invoiceNumber']))){
                $transaction->setInvoiceNumber($requestData['invoiceNumber']);
            }

            // ### Redirect urls
            // Set the urls that the buyer must be redirected to after 
            // payment approval/ cancellation.
            $baseUrl = isset($requestData['urls']['BaseUrl']) ? $requestData['urls']['BaseUrl'] : '';
            
            $redirectUrls = new RedirectUrls();
            if(isset($requestData['urls']['ReturnUrl'])){
                $redirectUrls->setReturnUrl($baseUrl . $requestData['urls']['ReturnUrl']);
            }
            if(isset($requestData['urls']['CancelUrl'])){
                $redirectUrls->setCancelUrl($baseUrl . $requestData['urls']['CancelUrl']);
            }
            
            // ### Payment
            // A Payment Resource; create one using the above types and intent set to sale 'sale'
            $payment = new Payment();
            
            if(isset($requestData['intent']) && !empty(trim($requestData['intent']))){
                $payment->setIntent($requestData['intent']);
            }
            if(!empty($this->checkEmptyObject((array)$payer))){
                $payment->setPayer($payer);
            }
            if(!empty($this->checkEmptyObject((array)$redirectUrls))){
                $payment->setRedirectUrls($redirectUrls);
            }
            if(!empty($this->checkEmptyObject((array)$transaction))){
                $payment->setTransactions(array($transaction));
            }
            if(isset($requestData['ExperienceProfileId']) && !empty(trim($requestData['ExperienceProfileId']))){
                $payment->setExperienceProfileId(trim($requestData['ExperienceProfileId']));
            }
            if(isset($requestData['NoteToPayer']) && !empty(trim($requestData['NoteToPayer']))){
                $payment->setNoteToPayer(trim($requestData['NoteToPayer']));
            }
            
            // ### Create Payment
            // Create a payment by calling the payment->create() method with a valid ApiContext. The return object contains the state.
            $requestArray = clone $payment;
            $payment->create($this->_api_context);

            // ### Get redirect url
            // The API response provides the url that you must redirect
            // the buyer to. Retrieve the url from the $payment->getApprovalLink()
            // method
            $approvalUrl = $payment->getApprovalLink();            
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYMENT'] = array('approvalUrl' => $approvalUrl, 'payment' => $payment->toArray());
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$payment->toJSON();            
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Creates a sale, an authorized payment to be captured later, or an order with third party payment.
     *
     * @param array $requestData
     * @return array|object
     */
    public function create_payment_with_paypal_third_party($requestData){
        
        try {
            // ### Payer
            // A resource representing a Payer that funds a payment
            // For paypal account payments, set payment method
            // to 'paypal'.
            $payer = new Payer();
            $payer->setPaymentMethod("paypal");

            // ### Itemized information
            // (Optional) Lets you specify item wise information
            $itemListArray = array();
            if (isset($requestData['orderItems'])) {                                        
                foreach ($this->checkEmptyObject($requestData['orderItems']) as $value) {
                    $item = new Item();
                    $array = array_filter($value);
                    if (count($array) > 0) {
                        $this->setArrayToMethods($array, $item);
                        array_push($itemListArray, $item);
                    }
                }
            }
            $itemList = new ItemList();
            if(!empty($itemListArray)){
                $itemList->setItems($itemListArray);
            }
            
            // ### Additional payment details
            // Use this optional field to set additional payment information such as tax, shipping charges etc.
            $details = new Details();
            if (isset($requestData['paymentDetails'])) {                
                $this->setArrayToMethods($this->checkEmptyObject($requestData['paymentDetails']), $details);
            }

            // ### Amount
            // Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            if (isset($requestData['amount'])) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['amount']), $amount);
            }
            
            $detailArray = $this->checkEmptyObject((array)$details); 
            if ( !empty($detailArray) ) {
                $amount->setDetails($details);
            }

            // ### Transaction
            // A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            if (!empty($this->checkEmptyObject((array)$amount))) {
                $transaction->setAmount($amount);
            }
            
            if (!empty($this->checkEmptyObject((array)$itemList))) {
                $transaction->setItemList($itemList);
            }
            if (isset($requestData['transaction'])){
                $this->setArrayToMethods($this->checkEmptyObject($requestData['transaction']), $transaction);
            }
                        
            if(isset($requestData['Payee']) && !empty($requestData['Payee'])){
                // Specify a payee with that user's email or merchant id
                // Merchant Id can be found at https://www.paypal.com/businessprofile/settings/                
                $payee = new Payee();
                $payee->setEmail($requestData['Payee']);
                $transaction->setPayee($payee);
            }
            
            
            if(isset($requestData['invoiceNumber']) && !empty(trim($requestData['invoiceNumber']))){
                $transaction->setInvoiceNumber($requestData['invoiceNumber']);
            }

            // ### Redirect urls
            // Set the urls that the buyer must be redirected to after 
            // payment approval/ cancellation.
            $baseUrl = isset($requestData['urls']['BaseUrl']) ? $requestData['urls']['BaseUrl'] : '';
            
            $redirectUrls = new RedirectUrls();
            if(isset($requestData['urls']['ReturnUrl'])){
                $redirectUrls->setReturnUrl($baseUrl . $requestData['urls']['ReturnUrl']);
            }
            if(isset($requestData['urls']['CancelUrl'])){
                $redirectUrls->setCancelUrl($baseUrl . $requestData['urls']['CancelUrl']);
            }
            
            // ### Payment
            // A Payment Resource; create one using the above types and intent set to sale 'sale'
            $payment = new Payment();
            
            if(isset($requestData['intent']) && !empty(trim($requestData['intent']))){
                $payment->setIntent($requestData['intent']);
            }
            if(!empty($this->checkEmptyObject((array)$payer))){
                $payment->setPayer($payer);
            }
            if(!empty($this->checkEmptyObject((array)$redirectUrls))){
                $payment->setRedirectUrls($redirectUrls);
            }
            if(!empty($this->checkEmptyObject((array)$transaction))){
                $payment->setTransactions(array($transaction));
            }
            if(isset($requestData['ExperienceProfileId']) && !empty(trim($requestData['ExperienceProfileId']))){
                $payment->setExperienceProfileId(trim($requestData['ExperienceProfileId']));
            }
            if(isset($requestData['NoteToPayer']) && !empty(trim($requestData['NoteToPayer']))){
                $payment->setNoteToPayer(trim($requestData['NoteToPayer']));
            }
            
            // ### Create Payment
            // Create a payment by calling the payment->create() method with a valid ApiContext. The return object contains the state.
            $requestArray = clone $payment;
            $payment->create($this->_api_context);

            // ### Get redirect url
            // The API response provides the url that you must redirect
            // the buyer to. Retrieve the url from the $payment->getApprovalLink()
            // method
            $approvalUrl = $payment->getApprovalLink();            
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYMENT'] = array('approvalUrl' => $approvalUrl, 'payment' => $payment->toArray());
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$payment->toJSON();            
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Creates a sale, an authorized payment to be captured later, or an order with saved card details.
     *
     * @param array $requestData
     * @param string $credit_card_id
     * @return array|object
     */
    public function create_payment_using_saved_card($requestData, $credit_card_id) {

        try {
            ///$card = new CreditCard();
            //$card->setId($credit_card_id);
            // ### Credit card token
            // Saved credit card id from CreateCreditCard.
            $creditCardToken = new CreditCardToken();
            $creditCardToken->setCreditCardId($credit_card_id);
            // ### FundingInstrument
            // A resource representing a Payer's funding instrument.
            // For stored credit card payments, set the CreditCardToken
            // field on this object.
            $fi = new FundingInstrument();
            if(!empty($this->checkEmptyObject((array)$creditCardToken))){
                $fi->setCreditCardToken($creditCardToken);
            }

            // ### Payer
            // A resource representing a Payer that funds a payment
            // For stored credit card payments, set payment method
            // to 'credit_card'.
            $payer = new Payer();
            $payer->setPaymentMethod("credit_card");
            if(!empty($this->checkEmptyObject((array)$fi))){
                $payer->setFundingInstruments(array($fi));
            }                                        

            // ### Itemized information
            // (Optional) Lets you specify item wise information
            $itemListArray = array();
            if(isset($requestData['orderItems'])){
                foreach ($this->checkEmptyObject($requestData['orderItems']) as $value) {
                    $item = new Item();
                    $array = array_filter($value);
                    if (count($array) > 0) {
                        $this->setArrayToMethods($array, $item);
                        array_push($itemListArray, $item);
                    }
                }
            }
            
            $itemList = new ItemList();
            if(!empty($this->checkEmptyObject($itemListArray))){
                $itemList->setItems($itemListArray);
            }
            // ### Additional payment details
            // Use this optional field to set additional payment information such as tax, shipping charges etc.

            if (isset($requestData['paymentDetails'])) {
                $details = new Details();
                $this->setArrayToMethods($this->checkEmptyObject($requestData['paymentDetails']), $details);
            }

            // ### Amount
            // Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            if (isset($requestData['amount'])) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['amount']), $amount);
            }
            if (!empty($this->checkEmptyObject((array)$details))) {
                $amount->setDetails($details);
            }

            // ### Transaction
            // A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            if(!empty($this->checkEmptyObject((array)$amount))){
                $transaction->setAmount($amount);
            }
            
            if (!empty($this->checkEmptyObject((array)$itemList))) {
                $transaction->setItemList($itemList);
            }
            if ($requestData['transaction']) {
                $this->setArrayToMethods($this->checkEmptyObject($requestData['transaction']), $transaction);
            }

            // ### Payment
            // A Payment Resource; create one using the above types and intent set to sale 'sale'
            $payment = new Payment();
            $payment->setIntent($requestData['intent']);
            
            if(!empty($this->checkEmptyObject((array)$payer))){
                $payment->setPayer($payer);
            }
            if(!empty($this->checkEmptyObject((array)$transaction))){
                $payment->setTransactions(array($transaction));
            }
            $requestArray = clone $payment;
            $payment->create($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYMENT'] = $payment->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$payment->toJSON();            
            return $returnArray;              
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {            
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Shows details for an authorization, by ID.
     *
     * @param string $authorizationId
     * @return array|object
     */
    public function get_authorization($authorizationId) {
        try {
            $result = Authorization::get($authorizationId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['AUTHORIZATION'] = $result->toArray();
            $returnArray['RAWREQUEST']='{id:'.$authorizationId.'}';
            $returnArray['RAWRESPONSE']=$result->toJSON();
            return $returnArray;                        
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Shows details for a payment, by ID.
     *
     * @param string $PaymentID
     * @return array|object
     */
    public function show_payment_details($PaymentID) {
        try {
            $payment = Payment::get($PaymentID, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYMENT'] = $payment->toArray();
            $returnArray['RAWREQUEST']='{id:'.$PaymentID.'}';
            $returnArray['RAWRESPONSE']=$payment->toJSON();
            return $returnArray;                                    
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Lists payments that are completed.
     *
     * @param array $params
     * @return array|object
     */
    public function list_payments($params) {
        try {
            $payments = Payment::all(array_filter($params), $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYMENTS'] = $payments->toArray();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Captures and processes an authorization, by ID. 
     *
     * @param string $authorizationId
     * @param array $amountArray
     * @return array|object
     */
    public function authorization_capture($authorizationId, $amountArray) {
        // # AuthorizationCapture

        /** @var Authorization $authorization */
        $authorization = new Authorization();

        try {
            $authId = $authorization->setId($authorizationId);
            $amt = new Amount();
            $this->setArrayToMethods($amountArray, $amt);
            ### Capture
            $capture = new Capture();
            $capture->setAmount($amt);
            // Perform a capture
            $requestArray = clone $authorization;
            $getCapture = $authorization->capture($capture, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['CAPTURE'] = $getCapture->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$getCapture->toJSON();  
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Voids, or cancels, an authorization, by ID. You cannot void a fully captured authorization.
     *
     * @param string $authorizationId
     * @return array|object
     */
    public function authorization_void($authorizationId) {
        // # VoidAuthorization
        try {
            // Lookup the authorization
            $authorization = Authorization::get($authorizationId, $this->_api_context);
            // Void the authorization
            $requestArray = clone  $authorization;
            $voidedAuth = $authorization->void($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['AUTH_VOID'] = $voidedAuth->toArray();
            $returnArray['RAWREQUEST']=$requestArray->toJSON();
            $returnArray['RAWRESPONSE']=$voidedAuth->toJSON();  
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Captures and processes an authorization, by ID. 
     *
     * @param string $authorizationCaptureId
     * @return array|object
     */
    public function get_capture($authorizationCaptureId) {
        // # GetCapture       
        try {
            $capture = Capture::get($authorizationCaptureId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['CAPTURE'] = $capture->toArray();
            $returnArray['RAWREQUEST']='{id:'.$authorizationCaptureId.'}';
            $returnArray['RAWRESPONSE']=$capture->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Shows details for an order, by ID.
     *
     * @param string $orderId
     * @return array|object
     */
    public function get_order($orderId){        
        // #Get Order Sample        
        try {
            $result = Order::get($orderId, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['ORDER'] = $result->toArray();
            $returnArray['RAWREQUEST']='{id:'.$orderId.'}';
            $returnArray['RAWRESPONSE']=$result->toJSON();
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * Authorizes an order, by ID.
     *
     * @param string  $orderId
     * @param array $amountArray
     * @return array|object
     */
    public function order_authorize($orderId,$amountArray){
        try {
            $order= new Order();
            $order->setId($orderId);
            $authorization = new Authorization();
            $amount = new Amount();
            $this->setArrayToMethods($amountArray, $amount);
            $authorization->setAmount($amount);
            $requestArray = clone $order;
            $result = $order->authorize($authorization,$this->_api_context);
            $authorizationId=$result->id;
            $returnArray['RESULT'] = 'Success';
            $returnArray['ORDER_AUTH'] = $result->toArray();
            $returnArray['AUTH_Id'] = $authorizationId;
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$result->toJSON();            
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Captures a payment for an order, by ID. 
     *
     * @param string $orderId
     * @param array $amountArray
     * @return array|object
     */
    public function order_capture($orderId,$amountArray){
        try {
            $order= new Order();
            $order->setId($orderId);
            
            $amount = new Amount();
            $this->setArrayToMethods($amountArray, $amount);
            
            $capture = new Capture();
            $capture->setIsFinalCapture(true);
            $capture->setAmount($amount);            
            $requestArray = clone $order;
            $result = $order->capture($capture, $this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['ORDER_CAPTURE'] = $result->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$result->toJSON();            
            return $returnArray;            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Voids, or cancels, an order, by ID. 
     *
     * @param string $orderId
     * @return array|object
     */
    public function order_void($orderId){
        try {            
            
            $order= new Order();
            $order->setId($orderId);
            $requestArray = clone $order;
            $result = $order->void($this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['ORDER_VOID'] = $result->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$result->toJSON();            
            return $returnArray;                       
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    /**
     * Refunds a captured payment, by ID.
     *
     * @param string $capture_id
     * @param array $amountArray
     * @param array $refundParameters
     * @return array|object
     */
    public function refund_capture($capture_id,$amountArray,$refundParameters){
        try {            
            
            $capture = new Capture();
            $capture->setId($capture_id);
            
            $amount = new Amount();
            $this->setArrayToMethods($amountArray, $amount);
            // ### Refund
            // Create a refund object indicating
            // refund amount and call the refund method
            
            $refundRequest = new RefundRequest();
            $refundRequest->setAmount($amount);
            $this->setArrayToMethods(array_filter($refundParameters), $refundRequest);                        
            $requestArray = clone $capture;
            $captureRefund = $capture->refundCapturedPayment($refundRequest,$this->_api_context);            
            $returnArray['RESULT'] = 'Success';
            $returnArray['REFUND_CAPTURE'] = $captureRefund->toArray();
            $returnArray['RAWREQUEST']=$requestArray;
            $returnArray['RAWRESPONSE']=$captureRefund->toJSON();            
            return $returnArray;                                  
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

    /**
     * 
     * @param string $refund_id
     * @return array|object
     */
    public function show_refund_details($refund_id){
        try {
            $refund = Refund::get($refund_id, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['REFUND'] = $refund->toArray();
            $returnArray['RAWREQUEST']='{refund_id :'.$refund_id.'}';
            $returnArray['RAWRESPONSE']=$refund->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * Reauthorizes a PayPal account payment, by authorization ID. To ensure that funds are still available, reauthorize a payment after the initial three-day honor period. Supports only the `amount` request parameter.
     * @param string $authorizationId
     * @param array $amount
     * @return array|object
     */
    public function reauthorization($authorizationId,$amount){
        try {
            $authorization = Authorization::get($authorizationId, $this->_api_context);
            
            $amt = new Amount();
            $amt->setCurrency($amount['Currency'])
                ->setTotal($amount['Total']);
            if(isset($amount['Details'])){
                $details = new Details();
                $detailArray = $this->checkEmptyObject($amount['Details']);
                if(!empty($detailArray)){
                    $this->setArrayToMethods($detailArray, $details);
                    $amt->setDetails($details);
                }


            }
            
            // ### Reauthorize with amount being reauthorized
            $authorization->setAmount($amt);
            $reAuthorization = $authorization->reauthorize($this->_api_context);
            
            $returnArray['RESULT'] = 'Success';
            $returnArray['REAUTHORIZATION'] = $reAuthorization->toArray();
            $returnArray['RAWREQUEST']=$authorization->toJSON();
            $returnArray['RAWRESPONSE']=$reAuthorization->toJSON();
            return $returnArray;
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }        
    }
    
    /**
     * Shows details for a sale, by ID. Returns only sales that were created through the REST API.
     * @param string $sale_id
     * @return array|object
     */
    public function get_sale($sale_id){
        // # GetSale       
        try {
            $sale = Sale::get($sale_id, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['SALE'] = $sale->toArray();
            $returnArray['RAWREQUEST']='{id:'.$sale_id.'}';
            $returnArray['RAWRESPONSE']=$sale->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
    
    /**
     * 
     * @param string $sale_id
     * @param array $amount
     * @param array $refundParameters
     * @return array|object
     */
    public function refund_sale($sale_id,$amount,$refundParameters){
        // Refund Sale       
        try {
            
            // ### Refund amount
            // Includes both the refunded amount (to Payer) 
            // and refunded fee (to Payee).
            
            $amt = new Amount();            
            $amt->setCurrency($amount['Currency'])
                ->setTotal($amount['Total']);
            if(isset($amount['Details'])){
                $details = new Details();
                $detailArray = $this->checkEmptyObject($amount['Details']);
                if(!empty($detailArray)){
                    $this->setArrayToMethods($detailArray, $details);
                    $amt->setDetails($details);
                }
            }
            
            // ### Refund object
            $refundRequest = new RefundRequest();
            $refundRequest->setAmount($amt);
            
            $requestArray = $this->checkEmptyObject($refundParameters);
            if(!empty($requestArray)){
                $this->setArrayToMethods($requestArray, $refundRequest);                
            }
                
            $sale = new Sale();
            $sale->setId($sale_id);
            
            $refundedSale = $sale->refundSale($refundRequest, $this->_api_context);

            $returnArray['RESULT'] = 'Success';
            $returnArray['SALE'] = $refundedSale->toArray();
            $returnArray['RAWREQUEST']=$refundRequest->toJSON();
            $returnArray['RAWRESPONSE']=$sale->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }
       
    /**
     * Partially updates a payment, by ID. 
     * You can update the amount, shipping address, invoice ID, and custom data.
     * You cannot update a payment after the payment executes.
     * @param string $paymentId
     * @param array $patchArray
     * @return array|object
     */
    public function update_payment($paymentId,$patchArray){
        try {            
            $patchRequest = new \PayPal\Api\PatchRequest();
            $payment = new Payment();
            $payment->setId($paymentId);            
                   
            $array1 = array();
            foreach ($patchArray as $value){
                $pathOperation = new \PayPal\Api\Patch();
                $ob=json_decode(json_encode($value['value']));
                $pathOperation->setOp($value['operation'])
                                 ->setPath($value['path'])
                                 ->setValue($ob);                
                $array1[] = $pathOperation;
            }            
            $patchRequest->setPatches($array1);                         
            $output = $payment->update($patchRequest, $this->_api_context);     
            if($output == true){
                $result = Payment::get($payment->getId(), $this->_api_context);
                $returnArray['RESULT'] = 'Success';
                $returnArray['PAYMENT'] = $result->toArray();
                $returnArray['RAWREQUEST']=$patchRequest->toJSON();
                $returnArray['RAWRESPONSE']=$output;
            }
            else{
                $returnArray['RESULT'] = 'Failed';
                $returnArray['RETURN'] = $output;
            }
            return $returnArray;                                  
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }


    public function execute_payment($paymentId,$payer_id,$amount=array()){

        $payment = Payment::get($paymentId, $this->_api_context);

        // ### Payment Execute
        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site

        $execution = new PaymentExecution();
        $execution->setPayerId($payer_id);

        if (isset($amount['Currency']) && !empty($amount['Currency']) && isset($amount['Total']) && !empty($amount['Total'])){

            $transaction = new Transaction();

            $amt = new Amount();
            $amt->setCurrency($amount['Currency'])
                ->setTotal($amount['Total']);

            $details = new Details();
            $detailArray = $this->checkEmptyObject($amount['Details']);
            if(!empty($detailArray)){
                $this->setArrayToMethods($detailArray, $details);
                $amt->setDetails($details);
            }
            $transaction->setAmount($amt);
            // Add the above transaction object inside our Execution object.
            $execution->addTransaction($transaction);
        }

        try {
            // Execute the payment
            $result = $payment->execute($execution, $this->_api_context);
            $returnArray['RESULT'] = 'Success';
            $returnArray['PAYMENT'] = $result->toArray();
            $returnArray['RAWREQUEST']=$execution->toJSON();
            $returnArray['RAWRESPONSE']=$result->toJSON();
            return $returnArray;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $this->createErrorResponse($ex);
        }

    }
}