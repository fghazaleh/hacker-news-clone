<?php

use FGhazaleh\Services\HackerNews\ItemDetailsService;
use FGhazaleh\Services\HackerNews\NewsStoriesService;
use Symfony\Component\HttpFoundation\Request;

//Request::setTrustedProxies(array('127.0.0.1'));

/**
 * @Route ("/")
 * @method : GET
 * */
$app->get('/', function () use ($app) {
    $stories = $app[NewsStoriesService::class]->topStories();
    $page = 1;
    return $app['twig']->render('news_stories/top_stories.html.twig',
        compact('stories','page')
    );
})
->bind('home');

/**
 * @Route ("/top-stories")
 * @method : GET
 * @optional:
 *  { page:int}
 * */
$app->get('/top-stories',function(Request $request) use ($app){

    $page = $request->get('page',1);
    $stories = $app[NewsStoriesService::class]->topStories($page);
    return $app['twig']->render(
        'news_stories/top_stories.html.twig',
        compact('stories','page')
    );

})->bind('top_stories');

/**
 * @Route ("/new-stories")
 * @method : GET
 * @optional:
 *  { page:int}
 * */
$app->get('/new-stories',function(Request $request) use ($app){

    $page = $request->get('page',1);
    $stories = $app[NewsStoriesService::class]->newStories($page);
    return $app['twig']->render(
        'news_stories/new_stories.html.twig',
        compact('stories','page')
    );

})->bind('new_stories');

/**
 * @Route ("/item/{id}")
 * @method : GET
 * @optional:
 *  { page:int}
 * */
$app->get('/item/{id}',function(int $id) use ($app){


    $story = $app[ItemDetailsService::class]->getItemById($id);
    return $app['twig']->render(
        'item_details/index.html.twig',
        compact('story')
    );

})->bind('item_details')->assert('id','\d+');