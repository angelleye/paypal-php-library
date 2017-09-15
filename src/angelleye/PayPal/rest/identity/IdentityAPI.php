<?php 

namespace angelleye\PayPal\rest\identity;

use PayPal\Api\OpenIdSession;
use PayPal\Api\OpenIdTokeninfo;;
use PayPal\Api\OpenIdUserinfo;
use PayPal\Exception\PayPalConnectionException;

class IdentityAPI {
    private $_api_context;
    public function __construct($configArray)
    {   // setup PayPal api context 
        $this->_api_context = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential($configArray['ClientID'],$configArray['ClientSecret'])
            );
    }
    
    public function GetUserConsentURL($requestData){
        //Get Authorization URL returns the redirect URL that could be used to get user's consent
        $redirectUrl = OpenIdSession::getAuthorizationUrl(
            $requestData['redirectUri'],$requestData['scope'],
            null,
            null,
            null,
            $this->_api_context
        );
        
        $returnArray['AuthorizationUrl'] = $redirectUrl;
        $returnArray['RAWREQUEST'] = json_encode(array_filter($requestData));
        return $returnArray;
    }
     
    public function GetUserInfo($requestData){
        try {
            $tokenInfo = new OpenIdTokeninfo();
            $tokenInfo = $tokenInfo->createFromRefreshToken(array('refresh_token' => $requestData['refreshToken']), $this->_api_context);
            $params = array('access_token' => $tokenInfo->getAccessToken());
            $userInfo = OpenIdUserinfo::getUserinfo($params, $this->_api_context);            
            $returnArray['userInfo'] = $userInfo->toArray();
            $returnArray['RAWREQUEST'] = json_encode($params);
            $returnArray['RAWRESPONSE'] = $userInfo->toJSON();
            return $returnArray;
        } catch (Exception $ex) {
            return $ex;   
        }
    }
}
?>