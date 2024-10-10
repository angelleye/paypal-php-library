<?php
namespace angelleye\PayPal;

class Rest extends PayPal
{
    private $api_url;
    private $access_token;

    public function __construct($paypal_config)
    {
        $this->client_id = $paypal_config['rest_client_id'];
        $this->client_secret = $paypal_config['rest_client_secret'];
        $this->api_url = $paypal_config['sandbox'] ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com';
        $this->access_token = $this->generate_access_token();
    }

    private function execute_curl($url, $headers, $post_fields = null, $custom_request = null): array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (!empty($post_fields)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }

        if (!empty($custom_request)) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $custom_request);
        }

        $response = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (!$response) {
            throw new \Exception("API request failed");
        }

        return [
            'http_status' => $http_status,
            'raw_response' => $response,
            'response_data' => json_decode($response, true)
        ];
    }

    private function generate_access_token()
    {
        $url = $this->api_url . "/v1/oauth2/token";
        $headers = [
            "Accept: application/json",
            "Accept-Language: en_US"
        ];
        $post_fields = "grant_type=client_credentials";
        $auth_header = base64_encode($this->client_id . ":" . $this->client_secret);

        $headers[] = "Authorization: Basic " . $auth_header;

        $response = $this->execute_curl($url, $headers, $post_fields);
        return $response['response_data']['access_token'];
    }

    public function send_request($method, $endpoint, $body = null): array
    {
        $url = $this->api_url . $endpoint;
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->access_token
        ];

        // Clean up the body by filtering out empty values
        $filtered_body = !empty($body) ? $this->remove_empty_values($body) : null;

        // Prepare JSON request
        $post_fields = !empty($filtered_body) ? json_encode($filtered_body) : null;

        // Execute the request and get the response
        return $this->execute_curl($url, $headers, $post_fields, $method);
    }

    public function create_order(array $order_data): array
    {
        // Prepare the request
        $url = '/v2/checkout/orders';

        // Clean up empty fields from the order data
        $filtered_order_data = $this->remove_empty_values($order_data);

        // JSON encode the filtered order data
        $json_request = json_encode($filtered_order_data);

        // Make the API call
        $response = $this->send_request('POST', $url, $filtered_order_data);

        // Prepare the result array with details
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
     * @param $order_id
     * @return array
     * @throws \Exception
     */
    public function get_order($order_id): array
    {
        $url = '/v2/checkout/orders/' . $order_id;

        $order_data = array('order_id' => $order_id);

        // Make the API call
        $response = $this->send_request('GET', $url, $order_data);

        // Prepare the result array
        $paypal_result = [
            'http_status' => $response['http_status'],
            'response_data' => $response['response_data'],
            'raw_request' => $this->api_url . $url,
            'raw_response' => $response['raw_response']
        ];

        return $paypal_result;
    }


    // Recursive function to remove empty values
    private function remove_empty_values($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->remove_empty_values($value);  // Recursively clean array elements
            }

            // Remove keys that are null, empty strings, or empty arrays
            if (empty($data[$key]) && $data[$key] !== '0') {
                unset($data[$key]);
            }
        }

        return $data;
    }
}