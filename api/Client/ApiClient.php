<?php

namespace Client;

require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');

use GuzzleHttp\Client;

class ApiClient
{
    private $baseUrl;
    private $client;
    private $cachePath; // Path to the cache directory

    public function __construct($baseUrl, $cachePath)
    {
        $this->baseUrl = $baseUrl;
        $this->client = new Client();
        $this->cachePath = $cachePath;
    }

    public function get($endpoint, $params = [], $cacheKey = null, $cacheTime = 43200)
    {
        if ($cacheKey !== null && $cachedResponse = $this->getFromCache($cacheKey)) {
            return $cachedResponse;
        }

        $url = $this->baseUrl . $endpoint;

        try {
            $response = $this->client->get($url, ['query' => $params]);
            $decodedResponse = json_decode($response->getBody()->getContents(), true);

            if ($cacheKey !== null) {
                $this->storeInCache($cacheKey, $decodedResponse, $cacheTime);
            }
            return $decodedResponse;
        } catch (\Exception $e) {
            // Handle errors or exceptions
            return ['error' => $e->getMessage()];
        }
    }

    public function post($endpoint, $data = [])
    {
        // Similar caching logic can be implemented for the post method
        // ...
    }

    // Add more methods for other HTTP methods as needed (e.g., put, delete)

    private function getFromCache($cacheKey)
    {
        $cacheFile = $this->cachePath . '/' . md5($cacheKey) . '.cache';

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < file_get_contents($cacheFile . '.ttl')) {
            return json_decode(file_get_contents($cacheFile), true);
        }

        return null;
    }

    private function storeInCache($cacheKey, $data, $cacheTime)
    {
        $cacheFile = $this->cachePath . '/' . md5($cacheKey) . '.cache';

        file_put_contents($cacheFile, json_encode($data));
        file_put_contents($cacheFile . '.ttl', time() + $cacheTime);
    }
}
