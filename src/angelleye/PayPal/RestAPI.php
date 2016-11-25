<?php       
class RestAPI {
    private $_api_context;
    public function __construct()
    {
        // setup PayPal api context 
        $this->_api_context = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AacwX_JLDp8RPX64Fylr_oPZaJajy5uh4Xgpz4bjylEY0HO2fryMGW7mAoXDIJuIpcYeKIXtbBZ7mxUP',     // ClientID
                'EI9fqz8TQmYVpv_WTdre-XSXMJcAkduSSDaRnsDIMWQTWdSVAs-X4nqT4uikE4wJ6YX-R-3XCtEeTklo'      // ClientSecret
            )
        );
    }
    
    public function StoreCreditCard($requestData){
        $creditCard = new \PayPal\Api\CreditCard();
        $creditCard->setType($requestData['creditCard']['creditCardType'])
                   ->setNumber($requestData['creditCard']['creditCardNumber'])
                   ->setExpireMonth($requestData['creditCard']['expireMonth'])
                   ->setExpireYear($requestData['creditCard']['expireYear'])
                   ->setCvv2($requestData['creditCard']['cvv'])
                   ->setFirstName($requestData['creditCard']['firstName'])
                   ->setLastName($requestData['creditCard']['lastName']);   
            
        $creditCard->setBillingAddress($requestData['BillingAddress']);  
        
          //  $creditCard->setMerchantId("MyStore1");
          //  $creditCard->setExternalCardId("CardNumber123" . uniqid());
          //  $creditCard->setExternalCustomerId("123123-myUser1@something.com");
            
        try {
            $creditCard->create($this->_api_context);
            return $creditCard;
        }
        catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception. 
            //REALLY HELPFUL FOR DEBUGGING
            return $ex->getData();        
        }        
    }
    
    
}
?>