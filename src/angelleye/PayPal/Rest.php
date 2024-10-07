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

    /**
     * Generic cURL request handler.
     * @param string $url The API endpoint URL.
     * @param string $method HTTP method (GET, POST, PATCH, etc.).
     * @param string $body Request body (could be JSON or URL encoded string).
     * @param array $headers HTTP headers to include in the request.
     * @return array Returns the HTTP status, response data, and raw response.
     */
    private function make_curl_request($url, $method = 'GET', $body = '', $headers = []) {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method); // Set the method dynamically

        if ($method !== 'GET') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        // Set headers
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        // Execute the request
        $response = curl_exec($curl);
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Close the curl session
        curl_close($curl);

        // Parse the response as JSON
        $response_data = json_decode($response, true);

        return [
            'http_status' => $http_status_code,
            'response_data' => $response_data,
            'raw_response' => $response
        ];
    }

    /**
     * Private function to retrieve the PayPal access token from PayPal's API.
     * @return mixed
     * @throws \Exception
     */
    private function retrieve_access_token() {
        $url = $this->api_endpoint . "/v1/oauth2/token";
        $auth_header = base64_encode("$this->rest_client_id:$this->rest_client_secret");

        $response = $this->make_curl_request($url, 'POST', "grant_type=client_credentials", [
            "Authorization: Basic $auth_header",
            "Content-Type: application/x-www-form-urlencoded",
        ]);

        if (!$response['response_data']) {
            throw new \Exception("Failed to obtain access token.");
        }

        return $response['response_data']['access_token'];
    }

    public function get_access_token() {
        return $this->access_token;
    }

    /**
     * Helper function to filter request data recursively. This ensures we only include provided params,
     * and if an array is empty or has only empty values, it will be excluded entirely.
     * @param array $data
     * @return array
     */
    private function filter_request_data($data) {
        $filteredData = array();

        foreach ($data as $key => $value) {
            // Recursively filter nested arrays
            if (is_array($value)) {
                $nestedFiltered = $this->filter_request_data($value);

                // Only include the array if it has non-empty values
                if (!empty($nestedFiltered)) {
                    $filteredData[$key] = $nestedFiltered;
                }
            } else {
                // Include non-null, non-empty scalar values
                if ($value !== null && $value !== '') {
                    $filteredData[$key] = $value;
                }
            }
        }

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

        // Make the cURL request using the reusable function
        $response = $this->make_curl_request($url, 'POST', $json_request, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->access_token,
            "PayPal-Request-Id: " . uniqid()  // Optional: A unique request ID for idempotency
        ]);

        // Prepare the result array
        $paypal_result = [
            'http_status' => $response['http_status'],
            'response_data' => $response['response_data'],
            'request_data' => json_decode($json_request, true),
            'raw_request' => $json_request,
            'raw_response' => $response['raw_response']
        ];

        return $paypal_result;
    }

    /**
     * Get Order Details by ID
     * @param string $order_id
     * @param string $fields (optional)
     * @return array
     */
    public function get_order_details($order_id, $fields = '') {
        $url = $this->api_endpoint . '/v2/checkout/orders/' . $order_id;

        // Append query parameters if "fields" is provided
        if (!empty($fields)) {
            $url .= '?fields=' . $fields;
        }

        // Make the cURL request using the reusable function
        $response = $this->make_curl_request($url, 'GET', '', [
            "Authorization: Bearer " . $this->access_token,
            "Content-Type: application/json"
        ]);

        // Prepare the result array
        $paypal_result = [
            'http_status' => $response['http_status'],
            'response_data' => $response['response_data'],
            'raw_response' => $response['raw_response']
        ];

        return $paypal_result;
    }

}