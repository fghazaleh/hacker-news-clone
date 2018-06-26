<?php namespace Tests\Fixtures;

use FGhazaleh\Exceptions\HttpClientException;
use FGhazaleh\Http\HttpClient\HttpResponseCollection;
use FGhazaleh\Http\HttpClient\HttpClientInterface;
use FGhazaleh\Http\HttpClient\HttpResponse;
use PHPUnit\Framework\Assert;
use Silex\Application;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class FakeHttpClient implements HttpClientInterface
{
    /**
     * @var string
     * */
    private $dataPath;

    private $url = null;
    private $method = null;

    public function __construct()
    {
        $this->dataPath = __DIR__ . '/../data/';
        $this->url = null;
    }

    /**
     * Use to swap the HttpClient with FakeHttpClient
     *
     * @param Application|HttpKernelInterface $app
     * */
    public static function fake(Application $app):void
    {
        $app[HttpClientInterface::class] = function ($app) {
            return new static();
        };
    }

    /**
     * @test
     * @param string $url
     */
    public function assertUrl(string $url)
    {
        Assert::assertSame($this->url,$url,sprintf('Assert Url expected to be the same of [%s].',$url));
    }

    /**
     * @test
     * @param string $method
     */
    public function assertMethod(string $method)
    {
        Assert::assertSame($this->method,$method,sprintf('Assert method expected to be the same of [%s].',$method));
    }
    /**
     * @param string $method ;
     * @param string $url ;
     * @param array $options ;
     *
     * @return HttpResponse
     * */
    public function request(string $method, string $url, array $options = []):HttpResponse
    {
        $this->url = $url;
        $this->method = $method;
        $filename = $this->getFilenameFromUrl($url);
        // read the local json file.
        return new HttpResponse(200, $this->readJsonFile($filename));
    }

    /**
     * Use to create a GET request and return response.
     *
     * @param string $url
     * @param array $params
     * @return HttpResponse
     */
    public function get(string $url, array $params = []):HttpResponse
    {
        return $this->request(HttpClientInterface::HTTP_METHOD_GET, $url, $params);
    }

    /**
     * @param string $url
     * @return string
     */
    private function getFilenameFromUrl(string $url):string
    {
        $array = explode('/', $url);
        $filename = end($array);

        // check if the filename is number, add items prefix.
        if (is_numeric(explode('.', $filename)[0])) {
            $filename = 'items/' . $filename;
        }
        return $filename;
    }

    /**
     * @param string $file
     * @return mixed
     * @throws HttpClientException
     */
    private function readJsonFile(string $file)
    {
        if (!file_exists($this->dataPath . $file)) {
            throw new HttpClientException(sprintf('File not exists on path [%s]', $this->dataPath . $file));
        }

        return json_decode(file_get_contents($this->dataPath . $file));
    }

    /**
     * @param string $method ;
     * @param array $urls ;
     * @return HttpResponseCollection
     * */
    public function requestAsync(string $method, array $urls):HttpResponseCollection
    {
        $response = new HttpResponseCollection;
        foreach ($urls as $url) {
            $filename = $this->getFilenameFromUrl($url);
            $response->addHttpResponse(new HttpResponse(200, $this->readJsonFile($filename)));
        }
        return $response;
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
        return $this->requestAsync(self::HTTP_METHOD_GET,$urls);
    }
}
