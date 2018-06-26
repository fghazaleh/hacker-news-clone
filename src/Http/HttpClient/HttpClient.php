<?php namespace FGhazaleh\Http\HttpClient;

use FGhazaleh\Exceptions\HttpClientException;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements HttpClientInterface
{

    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client = null, array $config = [])
    {
        if (is_null($client)) {
            $client = new Client($config);
        }
        $this->client = $client;
    }

    /**
     * @param string $method ;
     * @param string $url ;
     * @param array $options ;
     * @return HttpResponse
     * @throws HttpClientException
     */
    public function request(string $method, string $url, array $options = []):HttpResponse
    {
        try {
            $response = $this->client->get($url, $options);
            return $this->makeHttpResponse($response);
        } catch (\Exception $e) {
            throw new HttpClientException('HttpClient error:: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Request multiple urls as asynchronous requests,
     * return an array of HttpResponse
     *
     * @inheritdoc
     * @param string $method ;
     * @param array $urls ;
     * @return HttpResponseCollection
     * @throws HttpClientException
     */
    public function requestAsync(string $method, array $urls):HttpResponseCollection
    {
        $responses = new HttpResponseCollection;
        try {
            $promises = array_map(function ($url) {
                return $this->client->getAsync($url);
            }, $urls);

            $promisedResponses = Promise\unwrap($promises);

            $promisedResponses = Promise\settle($promisedResponses)->wait();

            //get only the success
            $promisedResponses = array_filter($promisedResponses, function ($response) {
                return (
                    $response['value']->getStatusCode() === 200 ||
                    $response['value']->getStatusCode() === 201
                );
            });

            //create a HttpResponse collection
            foreach ($promisedResponses as $response) {
                //if the response is valid
                if (isset($response['value']) && $response['value'] instanceof ResponseInterface) {
                    // add to collection
                    $responses->addHttpResponse($this->makeHttpResponse($response['value']));
                }
            }
        } catch (\Exception $e) {
            throw new HttpClientException('HttpClient error:: ' . $e->getMessage(), $e->getCode(), $e);
        }

        return $responses;
    }

    /**
     * Use to create a GET request and return response.
     *
     * @param string $url
     * @param array $params
     * @return HttpResponse
     * @throws HttpClientException
     */
    public function get(string $url, array $params = []):HttpResponse
    {
        return $this->request(HttpClientInterface::HTTP_METHOD_GET, $url, $params);
    }

    /**
     * create a new HttpResponse instance from the GuzzleHttp Response.
     * @param ResponseInterface $response
     * @return HttpResponse
     */
    private function makeHttpResponse(ResponseInterface $response):HttpResponse
    {
        return new HttpResponse(
            $response->getStatusCode(),
            json_decode($response->getBody()->getContents())
        );
    }

    /**
     * Create a multiple GET request as asynchronous requests,
     * return an array of HttpResponse
     * @param array $urls ;
     * @return HttpResponseCollection
     * @throws HttpClientException
     */
    public function getAsync(array $urls):HttpResponseCollection
    {
        return $this->requestAsync(self::HTTP_METHOD_GET, $urls);
    }
}
