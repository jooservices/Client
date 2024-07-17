<?php

namespace JOOservices\XClient\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\RedirectMiddleware;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Cache;
use JOOservices\XClient\Models\Request;
use JOOservices\XClient\Services\Response\XResponse;
use JOOservices\XClient\Services\Traits\WithClient;
use Psr\Http\Message\ResponseInterface;

class XClient
{
    use WithClient;

    private Client $client;

    public function __construct(protected array $requestOptions = [])
    {
        $this->requestOptions = array_merge($this->requestOptions, [
            'allow_redirects' => RedirectMiddleware::$defaultSettings,
            'http_errors' => true,
            'decode_content' => true,
            'verify' => true,
            'cookies' => false,
            'idn_conversion' => false,
        ]);
    }

    protected function convertToUTF8(array $array): array
    {
        array_walk_recursive($array, function (&$item) {
            if (! mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });

        return $array;
    }

    /**
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function make(): static
    {
        /**
         * @var XClientFactory $factory
         */
        $factory = app(XClientFactory::class);
        $this->client = $factory->enableRetries()
            ->make($this->requestOptions);

        return $this;
    }

    public function request(
        string $method,
        string $url,
        array $payload = [],
        array $options = []
    ): ResponseInterface {

        $payload = $this->convertToUTF8($payload);

        if ($method == 'GET') {
            $options['query'] = $payload;
        } else {
            switch ($this->contentType) {
                case 'application/x-www-form-urlencoded':
                    $options['form_params'] = $payload;
                    break;
                case 'json':
                default:
                    $options['json'] = $payload;
                    break;
            }
        }

        $id = serialize(func_get_args());

        return Cache::remember(
            $id,
            config('xclient.cache_interval', 3600),
            function () use ($method, $url, $options, $payload) {
                try {
                    $response = $this->client->request($method, $url, $options);
                    $body = $response->getBody()->getContents();

                    /**
                     * @TODO Body still using Stream
                     */
                    $xresponse = (new XResponse())->reset(
                        $response->getStatusCode(),
                        $response->getHeaders(),
                        $body,
                        $response->getProtocolVersion(),
                        $response->getReasonPhrase()
                    );

                    Request::create([
                        'ip' => request()->ip(),
                        'url' => $url,
                        'payload' => $payload,
                        'response' => $body,
                        'status' => $xresponse->getStatusCode(),
                    ]);

                    return $xresponse;
                } catch (ClientException|ServerException $exception) {
                    $statusCode = $exception->hasResponse()
                        ? $exception->getResponse()->getStatusCode()
                        : $exception->getCode();

                    $response = $exception->hasResponse()
                        ? $exception->getResponse()->getBody()->getContents()
                        : $exception->getMessage();

                    Request::create([
                        'ip' => request()->ip(),
                        'url' => $url,
                        'payload' => $payload,
                        'response' => $response,
                        'status' => $statusCode,
                    ]);

                    return (new XResponse())->reset(
                        $statusCode,
                        [],
                        $response,
                        $exception->getRequest()->getProtocolVersion(),
                        $exception->hasResponse()
                            ? $exception->getResponse()->getReasonPhrase()
                            : null,
                    );
                }
            });
    }
}
