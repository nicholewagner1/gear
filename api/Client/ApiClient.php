<?php

namespace Client;

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

use GuzzleHttp\Client;

class ApiClient
{
    private $baseUrl;
    private $client;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->client = new Client();
    }

    public function get($endpoint, $params = [])
    {
        $url = $this->baseUrl . $endpoint;

        try {
            $response = $this->client->get($url, ['query' => $params]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            // Handle errors or exceptions
            return ['error' => $e->getMessage()];
        }
    }

    public function post($endpoint, $data = [])
    {
        $url = $this->baseUrl . $endpoint;

        try {
            $response = $this->client->post($url, ['json' => $data]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            // Handle errors or exceptions
            return ['error' => $e->getMessage()];
        }
    }

    // Add more methods for other HTTP methods as needed (e.g., put, delete)
}
