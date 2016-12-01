<?php namespace angelleye\PayPal\rest\payments;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentCard;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;

class PaymentAPI {
    private $_api_context;
    public function __construct($configArray)
    {   // setup PayPal api context 
        $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'],$configArray['ClientSecret'])
            );
    }
    
    public function payment_create($requestData){                         
        try {
            // ### PaymentCard
            // A resource representing a payment card that can be used to fund a payment.
            $card = new PaymentCard();          
            $this->setArrayToMethods(array_filter($requestData['paymentCard']), $card);
            $this->setArrayToMethods(array("BillingAddress"=>array_filter($requestData['billingAddress'])), $card);    

            // ### FundingInstrument
            // A resource representing a Payer's funding instrument.
            $fi = new FundingInstrument();
            $fi->setPaymentCard($card);

            // ### Payer
            // A resource representing a Payer that funds a payment
            $payer = new Payer();
            $payer->setPaymentMethod("credit_card")
                ->setFundingInstruments(array($fi));

            // ### Itemized information
            // (Optional) Lets you specify item wise information
            $itemListArray= array();
            foreach ($requestData['orderItems'] as $value) {
                    $item = new Item();
                    $array=array_filter($value);
                    if(count($array)>0){
                        $this->setArrayToMethods($array, $item);
                        array_push($itemListArray, $item);
                    } 
            }
            $itemList = new ItemList();
            $itemList->setItems($itemListArray);        

            // ### Additional payment details
            // Use this optional field to set additional payment information such as tax, shipping charges etc.

            if(count(array_filter($requestData['paymentDetails']))>0){
               $details = new Details();
               $this->setArrayToMethods(array_filter($requestData['paymentDetails']),$details);
            }

            // ### Amount
            // Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            if(count(array_filter($requestData['amount']))>0){
               $this->setArrayToMethods(array_filter($requestData['amount']),$amount);
            }
            if(!empty ($details)){
                $amount->setDetails($details);
            }

            // ### Transaction
            // A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            $transaction->setAmount($amount);
            if(!empty($itemListArray)){
                $transaction->setItemList($itemList);
            }
            if(count(array_filter($requestData['transaction']))>0){
              $this->setArrayToMethods(array_filter($requestData['transaction']),$transaction);
            }    

            // ### Payment
            // A Payment Resource; create one using the above types and intent set to sale 'sale'
            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setTransactions(array($transaction));

            // ### Create Payment
            // Create a payment by calling the payment->create() method with a valid ApiContext. The return object contains the state.
            $payment->create($this->_api_context);
            return $payment;
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            return $ex->getData();    
        }
        
    }
    
    public function create_payment_with_paypal($requestData){
        
        try{
            // ### Payer
            // A resource representing a Payer that funds a payment
            // For paypal account payments, set payment method
            // to 'paypal'.
            $payer = new Payer();
            $payer->setPaymentMethod("paypal");
            
            // ### Itemized information
            // (Optional) Lets you specify item wise information
            $itemListArray= array();
            foreach ($requestData['orderItems'] as $value) {
                    $item = new Item();
                    $array=array_filter($value);
                    if(count($array)>0){
                        $this->setArrayToMethods($array, $item);
                        array_push($itemListArray, $item);
                    } 
            }
            $itemList = new ItemList();
            $itemList->setItems($itemListArray);        

            // ### Additional payment details
            // Use this optional field to set additional payment information such as tax, shipping charges etc.

            if(count(array_filter($requestData['paymentDetails']))>0){
               $details = new Details();
               $this->setArrayToMethods(array_filter($requestData['paymentDetails']),$details);
            }

            // ### Amount
            // Lets you specify a payment amount. You can also specify additional details such as shipping, tax.
            $amount = new Amount();
            if(count(array_filter($requestData['amount']))>0){
               $this->setArrayToMethods(array_filter($requestData['amount']),$amount);
            }
            if(!empty ($details)){
                $amount->setDetails($details);
            }

            // ### Transaction
            // A transaction defines the contract of a payment - what is the payment for and who is fulfilling it.
            $transaction = new Transaction();
            $transaction->setAmount($amount);
            if(!empty($itemListArray)){
                $transaction->setItemList($itemList);
            }
            if(count(array_filter($requestData['transaction']))>0){
               $this->setArrayToMethods(array_filter($requestData['transaction']),$transaction);
            }    
            
            // ### Redirect urls
            // Set the urls that the buyer must be redirected to after 
            // payment approval/ cancellation.
            $baseUrl = $requestData['urls']['BaseUrl'];
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl($baseUrl.$requestData['urls']['ReturnUrl'])
                ->setCancelUrl($baseUrl.$requestData['urls']['CancelUrl']);

            // ### Payment
            // A Payment Resource; create one using the above types and intent set to sale 'sale'
            $payment = new Payment();
            $payment->setIntent('sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)    
                ->setTransactions(array($transaction));

            // ### Create Payment
            // Create a payment by calling the payment->create() method with a valid ApiContext. The return object contains the state.
            $payment->create($this->_api_context);
            
            // ### Get redirect url
            // The API response provides the url that you must redirect
            // the buyer to. Retrieve the url from the $payment->getApprovalLink()
            // method
            $approvalUrl = $payment->getApprovalLink();
            return array('approvalUrl'=>$approvalUrl , 'payment' => $payment);   
        } catch (\PayPal\Exception\PayPalConnectionException  $ex) {
            //return $ex->getData(); 
        }    
    }
    
    public function setArrayToMethods($array,$object){
        foreach ($array as $key => $val){
            $method = 'set'.$key;
            if(!empty($val)){
                if (method_exists($object, $method))
                {                   
                     $object->$method($val);
                }            
            }
        }
        return TRUE;
    }
}
?>