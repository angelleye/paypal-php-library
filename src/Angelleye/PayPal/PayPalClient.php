<?php

namespace Angelleye\PayPal;

class PayPalClient {
    private $clientId;
    private $clientSecret;
    private $baseUrl;
    private $accessToken;

    /**
     * Constructor to initialize the PayPal client.
     */
    public function __construct($clientId, $clientSecret, $isSandbox = true) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->baseUrl = $isSandbox ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com';
        $this->authenticate();
    }

    /**
     * Authenticate with PayPal API to get the access token.
     */
    private function authenticate() {
        $url = "$this->baseUrl/v1/oauth2/token";
        $headers = [
            'Authorization: Basic ' . base64_encode("{$this->clientId}:{$this->clientSecret}"),
            'Content-Type: application/x-www-form-urlencoded'
        ];

        $response = $this->request('POST', $url, $headers, 'grant_type=client_credentials');
        $this->accessToken = $response['access_token'] ?? null;
    }

    /**
     * Make a generic HTTP request to the PayPal API.
     */
    private function request($method, $endpoint, $headers = [], $body = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($body) ? json_encode($body) : $body);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception("cURL Error: $error");
        }

        $decodedResponse = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMessage = $decodedResponse['message'] ?? 'An error occurred with the PayPal API.';
            $errorDetails = $decodedResponse['details'] ?? [];
            throw new \Exception("PayPal API Error ($httpCode): $errorMessage", $httpCode);
        }

        return $decodedResponse;
    }

    /**
     * GET request.
     */
    public function get($endpoint) {
        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json"
        ];
        return $this->request('GET', $this->baseUrl . $endpoint, $headers);
    }

    /**
     * POST request.
     */
    public function post($endpoint, $body = []) {
        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json"
        ];
        return $this->request('POST', $this->baseUrl . $endpoint, $headers, $body);
    }

    /**
     * PATCH request.
     */
    public function patch($endpoint, $body = []) {
        $headers = [
            "Authorization: Bearer {$this->accessToken}",
            "Content-Type: application/json"
        ];
        return $this->request('PATCH', $this->baseUrl . $endpoint, $headers, $body);
    }
}

