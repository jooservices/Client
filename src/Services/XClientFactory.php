<?php

namespace JOOservices\XClient\Services;

use GuzzleHttp\Client;
use Illuminate\Contracts\Container\BindingResolutionException;
use JOOservices\XClient\Services\Traits\WithHandler;
use JOOservices\XClient\Services\Traits\WithHistory;
use JOOservices\XClient\Services\Traits\WithMocking;

class XClientFactory
{
    use WithHandler;
    use WithHistory;
    use WithMocking;

    protected Client $client;

    /**
     * @throws BindingResolutionException
     */
    public function make(array $options = []): Client
    {
        if (! isset($options['handler'])) {
            $options['handler'] = $this->getHandler();
        }

        $this->client = app()
            ->makeWith(Client::class, ['config' => $options]);

        return $this->client;
    }
}
