<?php

namespace FGhazaleh\Services\HackerNews;

use FGhazaleh\Entity\Comment;
use FGhazaleh\Entity\HackerNewsEntityFactory;
use FGhazaleh\Entity\Story;
use FGhazaleh\Exceptions\InvalidArgsException;
use FGhazaleh\Http\HttpClient\HttpResponse;
use FGhazaleh\Http\HttpClient\HttpResponseCollection;
use FGhazaleh\Support\Collection\Collection;

class NewsStoriesService extends BaseHackerNewsService
{
    /**
     * Get a collection of top news stories,
     * pagination supported.
     *
     * @param int $page current page number
     * @return Collection
     * @throws \FGhazaleh\Exceptions\HttpClientException
     * @throws InvalidArgsException
     */
    public function topStories(int $page = 1):Collection
    {
        /*
         * @todo list: should be cached for 10 min.
         */
        $ids = $this->fetchTopStoriesIds()
            ->slice(
                $this->getPageOffset($page),
                $this->getPerPage()
            );
        return $this->fetchNewsStoriesDetailFromIds($ids);
    }

    /**
     * Get a collection of top news stories,
     * pagination supported.
     *
     * @param int $page current page number
     * @return Collection
     * @throws \FGhazaleh\Exceptions\HttpClientException
     * @throws InvalidArgsException
     */
    public function newStories(int $page = 1):Collection
    {
        /*
         * @todo list: should be cached for 10 min.
         */
        $ids = $this->fetchNewStoriesIds()
            ->slice(
                $this->getPageOffset($page),
                $this->getPerPage()
            );
        return $this->fetchNewsStoriesDetailFromIds($ids);
    }

    /**
     * Get news stories detail based on stories ids.
     *
     * @param Collection $ids
     * @return Collection
     * @throws \FGhazaleh\Exceptions\HttpClientException
     */
    private function fetchNewsStoriesDetailFromIds(Collection $ids):Collection
    {
        if (!isset($this->config['urls']['item'])) {
            throw new InvalidArgsException('"item" key is missing in config file.');
        }
        // get the item url from config file.
        $url = $this->config['urls']['item'];

        // create item url from list of ids.
        $ids->map(function ($id) use ($url) {
            return sprintf($url, $id);
        });

        //create async request for all urls.
        $response = $this->httpClient->getAsync($ids->all());

        //cast as entity
        return $this->createEntityCollectionFrom($response);
    }

    /**
     * Create HackerNewsEntity collection from HttpResponseCollection,
     * By using the HackerNewsEntityFactory, it creates Story,Comment entity.
     *
     * @param HttpResponseCollection $responseCollection
     * @return Collection
     */
    private function createEntityCollectionFrom(HttpResponseCollection $responseCollection):Collection
    {
        $entityCollection = new Collection();
        $responseCollection->each(function (HttpResponse $item) use ($entityCollection) {
            //Use HackerNewsEntity factory to create proper instance of entity.
            $entityCollection->add(HackerNewsEntityFactory::make($item));
        });
        return $entityCollection;
    }

    /**
     * Fetch TopStories Ids from API.
     *
     * @return Collection
     * @throws InvalidArgsException
     */
    private function fetchTopStoriesIds():Collection
    {
        if (!isset($this->config['urls']['top_stories'])) {
            throw new InvalidArgsException('"top_stories" key is missing in config file.');
        }
        // get the top_stories url from config.
        $url = $this->config['urls']['top_stories'];
        // fetch the data from server.
        $response = $this->httpClient->get($url);
        if (!$response->ok()) {
            throw new InvalidArgsException(sprintf("Something went wrong, unable to fetch data from url [%d]", $url));
        }
        // create a collection from result.
        return Collection::make($response->getResponse());
    }

    /**
     * Fetch NewStories Ids from API.
     *
     * @return Collection
     * @throws InvalidArgsException
     */
    private function fetchNewStoriesIds():Collection
    {
        if (!isset($this->config['urls']['new_stories'])) {
            throw new InvalidArgsException('"new_stories" key is missing in config file.');
        }
        // get the top_stories url from config.
        $url = $this->config['urls']['new_stories'];
        // fetch the data from server.
        $response = $this->httpClient->get($url);
        if (!$response->ok()) {
            throw new InvalidArgsException(sprintf("Something went wrong, unable to fetch data from url [%d]", $url));
        }
        // create a collection from result.
        return Collection::make($response->getResponse());
    }
}
