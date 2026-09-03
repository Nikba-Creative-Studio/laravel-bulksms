<?php

declare(strict_types=1);

namespace Nikba\BulkSms\Http\Clients;

use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Nikba\BulkSms\Exceptions\BulkSmsException;
use Nikba\BulkSms\Exceptions\BulkSmsRequestException;

/**
 * Thin, testable wrapper around Laravel's HTTP client for the BulkSMS API.
 *
 * Authentication, base URL, timeouts and error handling live here so the rest
 * of the package deals in plain paths and payloads.
 */
class BulkSmsClient
{
    /**
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        protected readonly HttpFactory $http,
        protected readonly array $config,
    ) {}

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function get(string $path, array $query = []): array
    {
        return $this->decode(
            $this->handle($this->request()->get($path, $query))
        );
    }

    /**
     * @param  array<string, mixed>|list<mixed>  $payload
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function post(string $path, array $payload = [], array $query = []): array
    {
        $request = $this->request();

        if ($query !== []) {
            $request = $request->withQueryParameters($query);
        }

        return $this->decode(
            $this->handle($request->post($path, $payload))
        );
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<string, mixed>
     */
    public function delete(string $path, array $query = []): array
    {
        return $this->decode(
            $this->handle($this->request()->delete($path, $query))
        );
    }

    protected function request(): PendingRequest
    {
        $request = $this->http
            ->baseUrl(rtrim((string) ($this->config['base_url'] ?? 'https://api.bulksms.com/v1'), '/'))
            ->timeout((int) ($this->config['timeout'] ?? 30))
            ->acceptJson()
            ->asJson();

        $times = (int) ($this->config['retry']['times'] ?? 0);
        if ($times > 0) {
            $request = $request->retry($times, (int) ($this->config['retry']['sleep'] ?? 200));
        }

        return $this->authenticate($request);
    }

    protected function authenticate(PendingRequest $request): PendingRequest
    {
        $tokenId = $this->config['token_id'] ?? null;
        $tokenSecret = $this->config['token_secret'] ?? null;

        if (filled($tokenId) && filled($tokenSecret)) {
            return $request->withBasicAuth((string) $tokenId, (string) $tokenSecret);
        }

        // Legacy support: a single, already Base64-encoded "tokenId:secret" value.
        $apiKey = $this->config['api_key'] ?? null;
        if (filled($apiKey)) {
            return $request->withHeaders(['Authorization' => 'Basic '.$apiKey]);
        }

        throw BulkSmsException::missingCredentials();
    }

    protected function handle(Response $response): Response
    {
        if ($response->failed()) {
            throw BulkSmsRequestException::fromResponse($response);
        }

        return $response;
    }

    /**
     * @return array<string, mixed>
     */
    protected function decode(Response $response): array
    {
        $decoded = $response->json();

        return is_array($decoded) ? $decoded : [];
    }
}
