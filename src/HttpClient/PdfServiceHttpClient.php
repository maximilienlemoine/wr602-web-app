<?php

namespace App\HttpClient;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PdfServiceHttpClient
{
    private HttpClientInterface $client;
    private string $pdfGeneratorUrl;

    public function __construct(HttpClientInterface $client, string $pdfGeneratorUrl)
    {
        $this->client = $client;
        $this->pdfGeneratorUrl = $pdfGeneratorUrl;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function post(string $path, array $options = []): string
    {
        $response = $this->client->request('POST', $this->pdfGeneratorUrl.'/'.$path, $options);

        return $response->getContent();
    }

}