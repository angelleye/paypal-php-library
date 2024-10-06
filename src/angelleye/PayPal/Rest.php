<?php
namespace angelleye\PayPal;

class Rest extends PayPal {

    private $accessToken;
    private $apiEndpoint;

    public function __construct($DataArray) {
        // Use the global config variables directly
        global $sandbox, $rest_client_id, $rest_client_secret;

        $this->sandbox = $DataArray['sandbox'] ?? true;
        $this->rest_client_id = $DataArray['rest_client_id'];
        $this->rest_client_secret = $DataArray['rest_client_secret'];

        // Define REST API endpoints for sandbox and production
        $this->apiEndpoint = $this->sandbox ? "https://api.sandbox.paypal.com" : "https://api.paypal.com";

        // Obtain access token via OAuth 2.0 using client ID and secret from config
        $this->accessToken = $this->retrieveAccessToken();
    }

    private function retrieveAccessToken() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->apiEndpoint . "/v1/oauth2/token");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_USERPWD, "$this->rest_client_id:$this->rest_client_secret");
        curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "Accept-Language: en_US",
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) {
            throw new \Exception("Failed to obtain access token.");
        }

        $jsonResponse = json_decode($response, true);
        return $jsonResponse['access_token'];
    }

    public function getAccessToken() {
        return $this->accessToken;  // Simply return the stored token from the constructor
    }
}