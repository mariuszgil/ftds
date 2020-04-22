<?php

namespace ProcessFlow;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;

/**
 * Class HttpResourceAvailabilityServiceClient
 * @package ProcessFlow
 */
class HttpResourceAvailabilityServiceClient
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
     * @return string
     */
    public function registerResource(/* ... */): string
    {
        $response = $this->httpClient->post(new Uri("{$this->baseUri}/resources"), [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => \json_encode([])
        ]);

        $body = $response->getBody();
        $object = \json_decode($body);

        // ...

        return $object->resource_id;
    }
}