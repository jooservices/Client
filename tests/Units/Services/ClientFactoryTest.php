<?php

namespace JOOservices\XClient\Tests\Units\Services;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Contracts\Container\BindingResolutionException;
use JOOservices\XClient\Services\XClientFactory;
use JOOservices\XClient\Services\Logger\XLoggerAdapter;
use JOOservices\XClient\Tests\TestCase;

class ClientFactoryTest extends TestCase
{
    /**
     * @throws BindingResolutionException
     * @throws GuzzleException
     */
    public function testMockFactory(): void
    {
        /**
         * @var XClientFactory $factory
         */
        $factory = app(XClientFactory::class);
        $client = $factory->appendResponse(200, 'test')
            ->make();

        $response = $client->get($this->faker->url);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test', $response->getBody()->getContents());
    }

    /**
     * @throws BindingResolutionException
     * @throws GuzzleException
     */
    public function testMockFactoryWithException(): void
    {
        /**
         * @var XClientFactory $factory
         */
        $factory = app(XClientFactory::class);
        $client = $factory->appendException('Exception', 'GET', $this->faker->url)
            ->make();

        $this->expectException(Exception::class);
        $client->get($this->faker->url);
    }

    /**
     * @throws GuzzleException
     * @throws BindingResolutionException
     */
    public function testEnableHistory(): void
    {
        /**
         * @var XClientFactory $factory
         */
        $factory = app(XClientFactory::class);
        $client = $factory
            ->appendResponse(200, 'test')
            ->enableHistory()
            ->make();

        $response = $client->request('GET', $this->faker->url);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('test', $response->getBody()->getContents());

        $history = $factory->getHistories()[0];

        $this->assertInstanceOf(Request::class, $history[0]['request']);
        $this->assertInstanceOf(Response::class, $history[0]['response']);
    }

    /**
     * @throws GuzzleException|BindingResolutionException
     */
    public function testEnableHistoryCount(): void
    {
        /**
         * @var XClientFactory $factory
         */
        $factory = app(XClientFactory::class);
        $client = $factory
            ->appendResponse(200, 'test')
            ->appendResponse(200, 'test')
            ->enableHistory()
            ->make();

        $client->request('GET', $this->faker->url);
        $client->request('GET', $this->faker->url);

        $this->assertCount(2, $factory->getHistories()[0]);
    }

    public function testEnableRetries(): void
    {
        $factory = app(XClientFactory::class);
        $client = $factory
            ->appendResponse(500, 'test')
            ->appendResponse(500, 'test')
            ->appendResponse(200, 'test')
            ->enableRetries()
            ->make();

        $response = $client->request('GET', $this->faker->url);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEnableLogging()
    {
        $factory = app(XClientFactory::class);
        $log = app(XLoggerAdapter::class);
        $client = $factory
            ->appendResponse(200, 'test')
            ->enableLogging($log)
            ->make();

        $client->request('GET', 'https://fake.com');

        $this->assertDatabaseCount('loggers', 1);
    }
}
