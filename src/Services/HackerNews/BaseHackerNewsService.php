<?php namespace FGhazaleh\Services\HackerNews;

use FGhazaleh\Http\HttpClient\HttpClientInterface;

abstract class BaseHackerNewsService
{
    /**
     * @var HttpClientInterface
     */
    protected $httpClient;
    /**
     * @var array
     */
    protected $config;

    /**
     * @param HttpClientInterface $httpClient
     * @param array $config hacker-news config array
     */
    public function __construct(HttpClientInterface $httpClient, array $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * Get the per page limit.
     *
     * @return int
     * */
    protected function getPerPage():int
    {
        if (!isset($this->config['per_page'])) {
            throw new \InvalidArgumentException('"per_page" key is missing in config file');
        }

        return (int)$this->config['per_page'];
    }

    /**
     * Get offset page.
     *
     * @param int $page
     * @return int
     * */
    protected function getPageOffset(int $page):int
    {
        $limit = $this->getPerPage();
        return $limit * ($page - 1);
    }
}
