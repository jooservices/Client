<?php

namespace JOOservices\XClient\Services\Logger;

use JOOservices\XLogger\Services\LoggerService;
use Psr\Log\LoggerInterface;

class XLoggerAdapter implements LoggerInterface
{
    public function emergency(\Stringable|string $message, array $context = []): void
    {
        app(LoggerService::class)->{__FUNCTION__}($message, $context);
    }

    public function alert(\Stringable|string $message, array $context = []): void
    {
        app(LoggerService::class)->{__FUNCTION__}($message, $context);
    }

    public function critical(\Stringable|string $message, array $context = []): void
    {
        app(LoggerService::class)->{__FUNCTION__}($message, $context);
    }

    public function error(\Stringable|string $message, array $context = []): void
    {
        app(LoggerService::class)->{__FUNCTION__}($message, $context);
    }

    public function warning(\Stringable|string $message, array $context = []): void
    {
        app(LoggerService::class)->{__FUNCTION__}($message, $context);
    }

    public function notice(\Stringable|string $message, array $context = []): void
    {
        app(LoggerService::class)->{__FUNCTION__}($message, $context);
    }

    public function info(\Stringable|string $message, array $context = []): void
    {
        app(LoggerService::class)->{__FUNCTION__}($message, $context);
    }

    public function debug(\Stringable|string $message, array $context = []): void
    {
        app(LoggerService::class)->{__FUNCTION__}($message, $context);
    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        app(LoggerService::class)->{__FUNCTION__}($level, $message, $context);
    }
}
