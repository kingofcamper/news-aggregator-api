<?php

namespace App\Http\Integrations\NewYorkTimes;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Http\Auth\QueryAuthenticator;

class NewYorkTimesConnector extends Connector
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return (string) config('services.new_york_times_api.url');
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
        return new QueryAuthenticator('api-key', (string) config('services.new_york_times_api.key'));
    }
}
