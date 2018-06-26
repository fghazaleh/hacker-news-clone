<?php namespace FGhazaleh\Http\HttpClient;

use FGhazaleh\Exceptions\HttpClientException;

interface HttpClientInterface
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    /**
     * @param string $method ;
     * @param string $url ;
     * @param array $options ;
     *
     * @return HttpResponse
     * */
    public function request(string $method, string $url, array $options = []):HttpResponse;

    /**
     * Request multiple urls as asynchronous requests,
     * return an array of HttpResponse
     *
     * @param string $method ;
     * @param array $urls ;
     * @return HttpResponseCollection
     * @throws HttpClientException
     */
    public function requestAsync(string $method, array $urls):HttpResponseCollection;

    /**
     * Create a multiple GET request as asynchronous requests,
     * return an array of HttpResponse
     * @param array $urls ;
     * @return HttpResponseCollection
     * @throws HttpClientException
     */
    public function getAsync(array $urls):HttpResponseCollection;

    /**
     * Use to create a GET request and return response.
     *
     * @param string $url
     * @param array $params
     * @return HttpResponse
     */
    public function get(string $url, array $params = []):HttpResponse;
}
