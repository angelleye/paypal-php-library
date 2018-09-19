<?php

namespace angelleye\PayPal\rest\payments;

use PayPal\Api\Amount;
use PayPal\Api\Address;
use PayPal\Api\Authorization;
use PayPal\Api\Capture;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Order;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Refund;
use PayPal\Api\RefundRequest;
use PayPal\Api\Transaction;
use \angelleye\PayPal\RestClass;

class PaymentAPI extends RestClass {

    private $_api_context;
    public function __construct($configArray) {        
        parent::__construct($configArray);
        $this->_api_context = $this->get_api_context();
    }

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
                $returnArray=$payment->toArray();
                $returnArray['RAWREQUEST']=$requestArray->toJSON();
                $returnArray['RAWRESPONSE']=$payment->toJSON();
                return $returnArray;
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $this->createErrorResponse($ex);
        }
    }

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
}
