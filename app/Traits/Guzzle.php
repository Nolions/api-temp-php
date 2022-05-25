<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

trait Guzzle
{
    /**
     * @var string
     */
    protected string $baseUrl;


    protected Client $client;

    public function __construct($baseUrl, array $options = [])
    {
        $this->baseUrl = $this->rtrimUrl($baseUrl);
        $this->client = $this->newGuzzleClient($baseUrl, $options);
    }

    /**
     * @param string $baseUrl
     * @param array $options
     * @return Client
     */
    public function newGuzzleClient(string $baseUrl, array $options = []): Client
    {
        return new Client(array_merge([
            'base_uri' => $this->rtrimUrl($baseUrl),
            'timeout' => 20,
        ], $options));
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $options
     * @return mixed
     * @throws GuzzleException
     */
    protected function request(string $method, string $path, array $options = [])
    {
        $resp = $this->client->request($method, $path, $options);
        $respBody = $this->getResponseBody($resp);

        return $respBody;
    }

    protected function guzzleRequest(Client $client, string $method, string $path, array $options = [])
    {
        $resp = $client->request($method, $path, $options);
        $respBody = $this->getResponseBody($resp);

        return $respBody;
    }

    protected function getResponseBody(ResponseInterface $resp)
    {
        return json_decode($resp->getBody());
    }

    protected function headers()
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    protected function buildQueryString(array $query): string
    {
        return http_build_query($query, null, '&', PHP_QUERY_RFC3986);
    }

    protected function buildFormBody(array $form): string
    {
        return http_build_query($form, '', '&');
    }

    protected function rtrimUrl(string $url): string
    {
        return rtrim($url, '/').'/';
    }

    /**
     * Thrown for 400 level errors, if the http_errors request option is set to true.
     *
     * @param ClientException $e
     * @return void
     * @throws Exception
     */
    protected function clientErrorHandle(ClientException $e)
    {
        throw new Exception('连接内部服务失败', error_code(6001), $e);
    }

    /**
     * Thrown for 500 level errors, if the http_errors request option is set to true.
     *
     * @param ServerException $e
     * @return void
     * @throws Exception
     */
    protected function serverErrorHandle(ServerException $e)
    {
        throw new Exception('连接内部服务失败', error_code(6002), $e);
    }

    /**
     * Thrown for 500 level errors, if the http_errors request option is set to true.
     *
     * @param TransferException $e
     * @return void
     * @throws Exception
     */
    protected function transferErrorHandle(Throwable $e)
    {
        throw new Exception('连接内部服务失败', error_code(6000), $e);
    }
}
