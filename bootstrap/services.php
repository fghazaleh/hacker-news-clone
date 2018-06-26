<?php

use FGhazaleh\Http\HttpClient\HttpClientInterface;
use FGhazaleh\Services\HackerNews\ItemDetailsService;
use FGhazaleh\Services\HackerNews\NewsStoriesService;
use FGhazaleh\Http\HttpClient\HttpClient;
use Silex\Application;

// Register the services here;
/**
 * @return HttpClient
 */
$app[HttpClientInterface::class] = function()
{
    return new HttpClient(new \GuzzleHttp\Client());
};

$app[NewsStoriesService::class] = function(Application $app)
{
    return new NewsStoriesService(
        $app[HttpClientInterface::class],$app['hacker-news']
    );
};

$app[ItemDetailsService::class] = function(Application $app)
{
    return new ItemDetailsService(
        $app[HttpClientInterface::class],$app['hacker-news']
    );
};
