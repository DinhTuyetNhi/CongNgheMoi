<?php
require __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;

class PayPalConfig {
    private $clientId = 'AZFNHtROTwhYacxrWSjlOokcOznPq6e7wC_gE3_YXoIZwjdM2sWLP7FfmUxQ8npVjclf8rDIVO2-b2w8';
    private $clientSecret = 'EMQf3zS26PEhoVHJfM8VhcRtDEEhaMPKSQ4yojsCzj--wGxcBei9ppgJRZjtOufkYmVcZo9mM7p8QYhQ';
    private $apiUrl = 'https://api-m.sandbox.paypal.com'; // Sandbox URL

    public function getAccessToken() {
        $client = new Client();
        $response = $client->post("{$this->apiUrl}/v1/oauth2/token", [
            'auth' => [$this->clientId, $this->clientSecret],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['access_token'];
    }

    public function getApiUrl() {
        return $this->apiUrl;
    }
}
?>