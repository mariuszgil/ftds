<?php

namespace ProcessFlow;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;

/**
 * Class HttpInventoryServiceClient
 * @package ProcessFlow
 */
class HttpInventoryServiceClient
{
    /** @var Client */
    private $httpClient;

    /** @var string */
    private $baseUri;

    public function __construct(string $baseUri)
    {
        $this->httpClient = new Client();
        $this->baseUri = $baseUri;
    }

    /**
     * @param string $name
     * @param string $description
     * @return string
     */
    public function registerProduct(string $name, string $description): string
    {
        $response = $this->httpClient->post(new Uri("{$this->baseUri}/products"), [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => \json_encode(['name' => $name, 'description' => $description])
        ]);

        $body = $response->getBody();
        $object = \json_decode($body);

        // ...

        return $object->product_id;
    }
}