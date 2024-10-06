<?php
namespace angelleye\PayPal;

class Rest extends PayPal {

    private $access_token;
    private $api_endpoint;

    public function __construct($DataArray) {
        // Use the global config variables directly
        global $sandbox, $rest_client_id, $rest_client_secret;

        $this->sandbox = $DataArray['sandbox'] ?? true;
        $this->rest_client_id = $DataArray['rest_client_id'];
        $this->rest_client_secret = $DataArray['rest_client_secret'];

        // Define REST API endpoints for sandbox and production
        $this->api_endpoint = $this->sandbox ? "https://api.sandbox.paypal.com" : "https://api.paypal.com";

        // Obtain access token via OAuth 2.0 using client ID and secret from config
        $this->access_token = $this->retrieve_access_token();
    }

    private function retrieve_access_token() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->api_endpoint . "/v1/oauth2/token");
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

        $json_response = json_decode($response, true);
        return $json_response['access_token'];
    }

    public function getAccessToken() {
        return $this->access_token;  // Simply return the stored token from the constructor
    }

    /**
     * Helper function to filter request data (this will ensure we only include provided params)
     * @param $data
     * @return array
     */
    private function filter_request_data($data) {
        $filteredData = array_filter($data, function ($value) {
            return $value !== null && $value !== '';  // Filter out null or empty values
        });

        return $filteredData;
    }

    /**
     * CreateOrder()
     * @param $paypal_request_data
     * @return array
     */
    public function create_order($paypal_request_data) {
        $url = $this->api_endpoint . '/v2/checkout/orders';

        // Filter out the provided data to create the request body
        $request_body = $this->filter_request_data($paypal_request_data);

        // Convert request body to JSON
        $json_request = json_encode($request_body);

        // Initialize cURL for the API call
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->access_token,
            "PayPal-Request-Id: " . uniqid()  // Optional: A unique request ID for idempotency
        ]);

        // Execute the request
        $response = curl_exec($curl);
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);  // Get the status code
        curl_close($curl);

        // Parse the response
        $json_response = json_decode($response, true);

        // Prepare the result array
        $paypal_result = [
            'http_status' => $http_status_code,
            'response_data' => $json_response,
            'request_data' => json_decode($json_request, true),
            'raw_request' => $json_request,
            'raw_response' => $response
        ];

        return $paypal_result;
    }
}