<?php

namespace JOOservices\XClient\Services\Traits;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

trait WithMocking
{
    protected array $mockingResponse = [];

    protected function hasMocking(): bool
    {
        return ! empty($this->mockingResponse);
    }

    /**
     * @return $this
     */
    public function appendResponse(
        int $statusCode,
        string $response,
        array $headers = [],
        string $version = '1.1',
        ?string $reason = null
    ): static {
        $this->mockingResponse[] = new Response(
            $statusCode,
            $headers,
            $response,
            $version,
            $reason
        );

        return $this;
    }

    /**
     * @return $this
     */
    public function appendException(
        string $message,
        string $method,
        string $uri,
    ): static {
        $this->mockingResponse[] = new RequestException(
            $message,
            new Request($method, $uri)
        );

        return $this;
    }
}
