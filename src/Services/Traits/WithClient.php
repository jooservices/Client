<?php

namespace JOOservices\XClient\Services\Traits;

trait WithClient
{
    protected string $contentType;

    public function setRequestOptions(array $options): static
    {
        $this->requestOptions = array_merge($this->requestOptions, $options);

        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->setRequestOptions([
            'headers' => $headers,
        ]);

        return $this;
    }

    public function setBaseUri(string $uri)
    {
        $this->setRequestOptions([
            'base_uri' => $uri,
        ]);

        return $this;
    }

    public function enableVerifySSL(bool $enableVerifySSL = true)
    {
        $this->setRequestOptions([
            'verify' => $enableVerifySSL,
        ]);

        return $this;
    }

    public function setContentType(string $contentType = 'json')
    {
        $this->contentType = $contentType;

        return $this;
    }
}
