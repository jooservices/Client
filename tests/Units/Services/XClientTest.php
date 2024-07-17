<?php

namespace JOOservices\XClient\Tests\Units\Services;

use JOOservices\XClient\Services\XClientFactory;
use JOOservices\XClient\Services\XClient;
use JOOservices\XClient\Tests\TestCase;

class XClientTest extends TestCase
{
    public function testGetSuccess()
    {
        app()->bind(XClientFactory::class, function () {
            $factory = new XClientFactory();
            $factory->appendResponse(200, 'fake');

            return $factory;
        });

        /**
         * @var XClient $client
         */
        $client = app(XClient::class)->make();
        $url = $this->faker->url;

        $response = $client->request(
            'GET',
            $url
        );

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('fake', $response->getBody()->getContents());

        $this->assertDatabaseHas('requests', [
            'response' => json_encode('fake'),
            'status' => 200,
            'url' => $url,
        ]);
    }

    public function testGetWith4xxException()
    {
        app()->bind(XClientFactory::class, function () {
            $factory = new XClientFactory();
            $factory->appendResponse(403, 'fake');
            $factory->appendResponse(403, 'fake');
            $factory->appendResponse(403, 'fake');
            $factory->appendResponse(403, 'fake');

            return $factory;
        });

        /**
         * @var XClient $client
         */
        $client = app(XClient::class)->make();
        $url = $this->faker->url;

        $response = $client->request('GET', $url);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(403, $response->getStatusCode());

        $this->assertDatabaseHas('requests', [
            'response' => json_encode('fake'),
            'status' => 403,
            'url' => $url,
        ]);
    }

    public function testGetWith5xxException()
    {
        app()->bind(XClientFactory::class, function () {
            $factory = new XClientFactory();
            $factory->appendResponse(500, 'fake');
            $factory->appendResponse(500, 'fake');
            $factory->appendResponse(500, 'fake');
            $factory->appendResponse(500, 'fake');

            return $factory;
        });

        /**
         * @var XClient $client
         */
        $client = app(XClient::class)->make();
        $url = $this->faker->url;

        $response = $client->request('GET', $url);

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(500, $response->getStatusCode());

        $this->assertDatabaseHas('requests', [
            'response' => json_encode('fake'),
            'status' => 500,
            'url' => $url,
        ]);
    }
}
