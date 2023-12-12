<?php

namespace App\Http\Integrations\NewsApi;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Http\Auth\QueryAuthenticator;


class NewsApiConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return (string) config('services.news_api.url');
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [
            'timeout' => 120,
        ];
    }

    /**
     * Default Api key parameter
     */
    protected function defaultAuth(): QueryAuthenticator
    {
        return new QueryAuthenticator('apiKey', (string) config('services.news_api.key'));
    }
}
