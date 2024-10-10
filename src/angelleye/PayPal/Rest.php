<?php
/**
 *
 */
namespace angelleye\PayPal;

class Rest extends PayPal
{
    private $client_id;
    private $client_secret;
    private $api_url;
    private $access_token;

    public function __construct($client_id, $client_secret, $sandbox = true)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->api_url = $sandbox ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com';
        $this->access_token = $this->generate_access_token();
    }

    private function execute_curl($url, $headers, $post_fields = null, $custom_request = null)
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
        curl_close($ch);

        if (!$response) {
            throw new Exception("API request failed");
        }

        return json_decode($response, true);
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
        return $response['access_token'];
    }

    public function send_request($method, $endpoint, $body = null)
    {
        $url = $this->api_url . $endpoint;
        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer " . $this->access_token
        ];

        $post_fields = !empty($body) ? json_encode($body) : null;

        return $this->execute_curl($url, $headers, $post_fields, $method);
    }

    public function create_order(array $order_data)
    {
        return $this->send_request('POST', '/v2/checkout/orders', $order_data);
    }
}