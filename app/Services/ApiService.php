<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiService
{
    protected $client;
    protected $apiKey;
    protected $baseUri;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('API_KEY');
        $this->baseUri = env('API_HOST');
    }

    public function fetchAllData($endpoint, $params = [])
    {
        $allData = [];
        $page = 1;

        do {
            try {
                $response = $this->client->get("{$this->baseUri}/api/{$endpoint}", [
                    'query' => array_merge([
                        'page' => $page,
                        'limit' => 100,
                        'key' => $this->apiKey,
                    ], $params),
                ]);

                $result = json_decode($response->getBody(), true);
                $allData = array_merge($allData, $result['data'] ?? []);
                $lastPage = $result['meta']['last_page'] ?? 1;
                $page++;
            } catch (RequestException $e) {
                \Log::error("API request failed for endpoint {$endpoint}: " . $e->getMessage());
                break;
            }
        } while ($page <= $lastPage);

        return $allData;
    }
}
