<?php namespace FGhazaleh\Entity;

use FGhazaleh\Entity\Contacts\HackerNewsEntityInterface;
use FGhazaleh\Exceptions\InvalidArgsException;
use FGhazaleh\Http\HttpClient\HttpResponse;

final class HackerNewsEntityFactory
{
    /**
     * @param HttpResponse $response
     *
     * @return HackerNewsEntityInterface
     * @throws InvalidArgsException
     */
    public static function make(HttpResponse $response): HackerNewsEntityInterface
    {
        $type = $response->getResponse()->type;

        switch ($type) {
            case 'story':
                return new Story($response->getResponse());
                break;
            case 'job':
                return new Job($response->getResponse());
                break;
            case 'comment':
                return new Comment($response->getResponse());
                break;
        }
        throw new InvalidArgsException(sprintf('Invalid entity type, [%s] type is not defined', $type));
    }
}
