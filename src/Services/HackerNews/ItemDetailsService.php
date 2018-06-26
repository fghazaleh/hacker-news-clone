<?php namespace FGhazaleh\Services\HackerNews;

use FGhazaleh\Entity\Contacts\HackerNewsEntityInterface;
use FGhazaleh\Entity\HackerNewsEntityFactory;
use FGhazaleh\Entity\Story;
use FGhazaleh\Exceptions\InvalidArgsException;

class ItemDetailsService extends BaseHackerNewsService
{

    /**
     * Get Story with its nested comments.
     *
     * @param int $id
     * @return Story
     *
     * @throws \FGhazaleh\Exceptions\HttpClientException
     * @throws InvalidArgsException
     */
    public function getItemById(int $id):Story
    {
        // get the item url from config file.
        $story = $this->fetchItemById($id);

        // get nested comments related to story,
        // and update the story instance.
        $this->fetchCommentsFromStoryKids(
            $story,
            $this->getPrefixItemUrl()
        );

        return $story;
    }

    /**
     * Get all comment children recursively.
     *
     * @param HackerNewsEntityInterface $item
     * @param string $prefixItemUrl
     * @throws \FGhazaleh\Exceptions\HttpClientException
     */
    private function fetchCommentsFromStoryKids(HackerNewsEntityInterface &$item, string $prefixItemUrl):void
    {
        //build the url from item id.
        $urls = array_map(function ($id) use ($prefixItemUrl) {
            return sprintf($prefixItemUrl, $id);
        }, $item->getKids());

        //create async request for all urls.
        $responses = $this->httpClient->getAsync($urls);

        //loop through the responses
        $responses->each(function ($response) use ($item, $prefixItemUrl) {
            //create proper Entity instance based on provided data
            $comment = HackerNewsEntityFactory::make($response);

            //if the entity has kids, recursive calling
            if ($comment->hasKids()) {
                $this->fetchCommentsFromStoryKids($comment, $prefixItemUrl);
            }
            $item->addChild($comment);
        });
    }

    /**
     * Fetch item from API using HttpClient service,
     * return Story Entity
     *
     * @param int $id Story item id
     * @return Story
     * @throws InvalidArgsException
     * */
    private function fetchItemById(int $id):Story
    {
        //build item url.
        $url = sprintf($this->getPrefixItemUrl(), $id);

        // fetch the data from server.
        $response = $this->httpClient->get($url);
        if (!$response->ok()) {
            throw new InvalidArgsException(
                sprintf("Something went wrong, unable to fetch data from url [%d]", $url)
            );
        }
        return new Story($response->getResponse());
    }

    /**
     * Get prefix item url from config,
     * throws an exception if key not found in config.
     *
     * @return string
     * @throws InvalidArgsException
     * */
    private function getPrefixItemUrl():string
    {
        if (!isset($this->config['urls']['item'])) {
            throw new InvalidArgsException('"item" key is missing in config file.');
        }
        return $this->config['urls']['item'];
    }
}
