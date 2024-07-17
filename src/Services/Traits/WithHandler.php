<?php

namespace JOOservices\XClient\Services\Traits;

use DateTime;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

trait WithHandler
{
    protected HandlerStack|MockHandler|null $handler;

    protected function getHandler(bool $useMock = false): HandlerStack|MockHandler
    {
        if (
            $useMock || $this->hasMocking()
        ) {
            $mockHandler = new MockHandler();
            foreach ($this->mockingResponse as $mocking) {
                $mockHandler->append($mocking);
            }
        }

        $this->handler = $this->handler ?? HandlerStack::create($mockHandler ?? null);

        return $this->handler;
    }

    public function pushMiddleware(callable $middleware, string $name = ''): static
    {
        $this->getHandler()
            ->push($middleware, $name);

        return $this;
    }

    public function enableRetries(int $maxRetries = 3, int $delayInSec = 1, int $minErrorCode = 500): static
    {
        $decider = function (
            int $retries,
            RequestInterface $request,
            ?ResponseInterface $response = null
        ) use (
            $maxRetries,
            $minErrorCode
        ): bool {
            return
                $retries < $maxRetries
                && $response !== null
                && $response->getStatusCode() >= $minErrorCode;
        };

        $delay = function (int $retries, ResponseInterface $response) use ($delayInSec): float|int {

            if ($this->hasMocking()) {
                return 1;
            }

            if (! $response->hasHeader('Retry-After')) {
                return $retries * $delayInSec * 1000;
            }

            $retryAfter = $response->getHeaderLine('Retry-After');

            if (! is_numeric($retryAfter)) {
                $retryAfter = (new DateTime($retryAfter))->getTimestamp() - time();
            }

            return (int) $retryAfter * 1000;
        };

        $this->pushMiddleware(Middleware::retry($decider, $delay));

        return $this;
    }

    public function enableLogging(
        LoggerInterface $logger,
        string $format = MessageFormatter::DEBUG,
        string $level = LogLevel::INFO
    ): self {
        return $this->pushMiddleware(
            Middleware::log($logger, new MessageFormatter($format), $level),
            'log'
        );
    }

    /**
     * @return $this
     */
    public function reset(): static
    {
        $this->handler = null;

        return $this;
    }
}
