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

class PaymentAPI {

    private $_api_context;

    public function __construct($configArray) {
        // setup PayPal api context 
        $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'], $configArray['ClientSecret'])
        );
    }

    public function payment_create($requestData) {
        try {
            // ### PaymentCard
            // A resource representing a payment card that can be used to fund a payment.
            $card = new PaymentCard();
            if($this->checkEmptyObject($requestData['paymentCard'])){
                $this->setArrayToMethods(array_filter($requestData['paymentCard']), $card);
            }
            if($this->checkEmptyObject($requestData['billingAddress'])){
                $this->setArrayToMethods(array("BillingAddress" => array_filter($requestData['billingAddress'])), $card);
            }            
            
            // ### FundingInstrument
            // A resource representing a Payer's funding instrument.
            $fi = new FundingInstrument();
            if($this->checkEmptyObject((array)$card)){
                $fi->setPaymentCard($card);           
            }
                       
            // ### Payer
            // A resource representing a Payer that funds a payment
            $payer = new Payer();
            $payer->setPaymentMethod("credit_card");
            if($this->checkEmptyObject((array)$fi)){
                $payer->setFundingInstruments(array($fi));
            }
             
            // ### Itemized information
            // (Optional) Lets you specify item wise information
            $itemListArray = array();
            if($this->checkEmptyObject($requestData['orderItems'])){
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
            if($this->checkEmptyObject($itemListArray)){
                $itemList->setItems($itemListArray);
            }            
            
            // ### Additional payment details
            // Use this optional field to set additional payment information such as tax, shipping charges etc.
            $details = new Details();
            if ($this->checkEmptyObject($requestData['paymentDetails'])) {                
                $this->setArrayToMethods(array_filter($requestData['paymentDetails']), $details);
            }            
            // ### Amount
            // Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            if ($this->checkEmptyObject($requestData['amount'])) {
                $this->setArrayToMethods(array_filter($requestData['amount']), $amount);
            }
            if ($this->checkEmptyObject((array)$details)) {
                $amount->setDetails($details);
            }

            // ### Transaction
            // A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            if($this->checkEmptyObject((array)$amount)){
                $transaction->setAmount($amount);
            }
            
            if ($this->checkEmptyObject((array)$itemList)) {
                $transaction->setItemList($itemList);
            }
            
            if ($this->checkEmptyObject($requestData['transaction'])) {
                $this->setArrayToMethods(array_filter($requestData['transaction']), $transaction);
            }

            // ### Payment
            // A Payment Resource; create one using the above types and intent set to sale 'sale'
            $payment = new Payment();
            if(!empty(trim($requestData['ExperienceProfileId']))){
                $payment->setExperienceProfileId($requestData['ExperienceProfileId']);
            }
            if(!empty(trim($requestData['NoteToPayer']))){
                $payment->setNoteToPayer($requestData['NoteToPayer']);
            }
            
            $payment->setIntent(trim($requestData['intent']));
            if($this->checkEmptyObject((array)$payer)){
                $payment->setPayer($payer);
            }
            if($this->checkEmptyObject((array)$transaction)){
                $payment->setTransactions(array($transaction));
            }            
            // ### Create Payment
            // Create a payment by calling the payment->create() method with a valid ApiContext. The return object contains the state.
            $payment->create($this->_api_context);           
            if ($requestData['intent'] == 'authorize') {
                $transactions = $payment->getTransactions();
                $relatedResources = $transactions[0]->getRelatedResources();
                $authorization = $relatedResources[0]->getAuthorization();
                return array('Authorization' => $authorization, 'Payment' => $payment);
            } else {
                return $payment;
            }
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            if($this->checkEmptyObject($requestData['orderItems'])){
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
            if($this->checkEmptyObject($itemListArray)){
                $itemList->setItems($itemListArray);
            }
            
            // ### Additional payment details
            // Use this optional field to set additional payment information such as tax, shipping charges etc.

            if ($this->checkEmptyObject($requestData['paymentDetails'])) {
                $details = new Details();
                $this->setArrayToMethods(array_filter($requestData['paymentDetails']), $details);
            }

            // ### Amount
            // Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            if ($this->checkEmptyObject($requestData['amount'])) {
                $this->setArrayToMethods(array_filter($requestData['amount']), $amount);
            }
            if ($this->checkEmptyObject((array)$details)) {
                $amount->setDetails($details);
            }

            // ### Transaction
            // A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            if ($this->checkEmptyObject((array)$amount)) {
                $transaction->setAmount($amount);
            }
            
            if ($this->checkEmptyObject((array)$itemList)) {
                $transaction->setItemList($itemList);
            }
            if ($this->checkEmptyObject($requestData['transaction'])) {
                $this->setArrayToMethods(array_filter($requestData['transaction']), $transaction);
            }
            if(!empty(trim($requestData['invoiceNumber']))){
                $transaction->setInvoiceNumber($requestData['invoiceNumber']);
            }

            // ### Redirect urls
            // Set the urls that the buyer must be redirected to after 
            // payment approval/ cancellation.
            $baseUrl = $requestData['urls']['BaseUrl'];
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($baseUrl . $requestData['urls']['ReturnUrl'])
                    ->setCancelUrl($baseUrl . $requestData['urls']['CancelUrl']);

            // ### Payment
            // A Payment Resource; create one using the above types and intent set to sale 'sale'
            $payment = new Payment();
            
            if(!empty(trim($requestData['intent']))){
                $payment->setIntent($requestData['intent']);
            }
            if($this->checkEmptyObject((array)$payer)){
                $payment->setPayer($payer);
            }
            if($this->checkEmptyObject((array)$redirectUrls)){
                $payment->setRedirectUrls($redirectUrls);
            }
            if($this->checkEmptyObject((array)$transaction)){
                $payment->setTransactions(array($transaction));
            }
            if(!empty(trim($requestData['ExperienceProfileId']))){
                $payment->setExperienceProfileId(trim($requestData['ExperienceProfileId']));
            }
            if(!empty(trim($requestData['NoteToPayer']))){
                $payment->setNoteToPayer(trim($requestData['NoteToPayer']));
            }
            
            // ### Create Payment
            // Create a payment by calling the payment->create() method with a valid ApiContext. The return object contains the state.
            $payment->create($this->_api_context);

            // ### Get redirect url
            // The API response provides the url that you must redirect
            // the buyer to. Retrieve the url from the $payment->getApprovalLink()
            // method
            $approvalUrl = $payment->getApprovalLink();
            return array('approvalUrl' => $approvalUrl, 'payment' => $payment);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            if($this->checkEmptyObject((array)$creditCardToken)){
                $fi->setCreditCardToken($creditCardToken);
            }

            // ### Payer
            // A resource representing a Payer that funds a payment
            // For stored credit card payments, set payment method
            // to 'credit_card'.
            $payer = new Payer();
            $payer->setPaymentMethod("credit_card");
            if($this->checkEmptyObject((array)$fi)){
                $payer->setFundingInstruments(array($fi));
            }                                        

            // ### Itemized information
            // (Optional) Lets you specify item wise information
            $itemListArray = array();
            if($this->checkEmptyObject($requestData['orderItems'])){
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
            if($this->checkEmptyObject($itemListArray)){
                $itemList->setItems($itemListArray);
            }
            // ### Additional payment details
            // Use this optional field to set additional payment information such as tax, shipping charges etc.

            if ($this->checkEmptyObject($requestData['paymentDetails'])) {
                $details = new Details();
                $this->setArrayToMethods(array_filter($requestData['paymentDetails']), $details);
            }

            // ### Amount
            // Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            if ($this->checkEmptyObject($requestData['amount'])) {
                $this->setArrayToMethods(array_filter($requestData['amount']), $amount);
            }
            if ($this->checkEmptyObject((array)$details)) {
                $amount->setDetails($details);
            }

            // ### Transaction
            // A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            if($this->checkEmptyObject((array)$amount)){
                $transaction->setAmount($amount);
            }
            
            if ($this->checkEmptyObject((array)$itemList)) {
                $transaction->setItemList($itemList);
            }
            if ($this->checkEmptyObject($requestData['transaction'])) {
                $this->setArrayToMethods(array_filter($requestData['transaction']), $transaction);
            }

            // ### Payment
            // A Payment Resource; create one using the above types and intent set to sale 'sale'
            $payment = new Payment();
            $payment->setIntent($requestData['intent']);
            
            if($this->checkEmptyObject((array)$payer)){
                $payment->setPayer($payer);
            }
            if($this->checkEmptyObject((array)$transaction)){
                $payment->setTransactions(array($transaction));
            }
            
            $payment->create($this->_api_context);
            return $payment;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function get_authorization($authorizationId) {
        try {
            $result = Authorization::get($authorizationId, $this->_api_context);
            return $result;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function show_payment_details($PaymentID) {
        try {
            $payment = Payment::get($PaymentID, $this->_api_context);
            return $payment;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function list_payments($params) {
        try {
            $payments = Payment::all(array_filter($params), $this->_api_context);
            return $payments;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            $getCapture = $authorization->capture($capture, $this->_api_context);
            return $getCapture;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function authorization_void($authorizationId) {
        // # VoidAuthorization
        try {
            // Lookup the authorization
            $authorization = Authorization::get($authorizationId, $this->_api_context);
            // Void the authorization
            $voidedAuth = $authorization->void($this->_api_context);
            return $voidedAuth;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function get_capture($authorizationCaptureId) {
        // # GetCapture       
        try {
            $capture = Capture::get($authorizationCaptureId, $this->_api_context);
            return $capture;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function get_order($orderId){        
        // #Get Order Sample        
        try {
            $result = Order::get($orderId, $this->_api_context);
            return $result;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            $result = $order->authorize($authorization,$this->_api_context);
            $authorizationId=$result->id;           
            return array("Order Authorize"=>$result,'Authorization Id'=>$authorizationId);
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            
            $result = $order->capture($capture, $this->_api_context);
            return $result;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }
    
    public function order_void(){
        try {            
            
            $order= new Order();
            $order->setId($orderId);
            $result = $order->void($this->_api_context);
            return $result;
            
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
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
            
            $captureRefund = $capture->refundCapturedPayment($refundRequest,$this->_api_context);
            return $captureRefund;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex->getData();
        }
    }

    public function setArrayToMethods($array, $object) {
        foreach ($array as $key => $val) {
            $method = 'set' . $key;
            if (!empty($val)) {
                if (method_exists($object, $method)) {
                    $object->$method($val);
                }
            }
        }
        return TRUE;
    }
    
    public function checkEmptyObject($array){
        if(count(array_filter($array)) > 0){
            return TRUE;
        }
        else {
            return FALSE;
        }
    }

}
?>