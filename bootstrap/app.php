<?php

use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;

$app = new Application();


$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());

//load services
require __DIR__.'/services.php';

$app['twig'] = $app->extend('twig', function (Twig_Environment $twig, $app) {
    // add custom globals, filters, tags, ...
    return $twig;
});

return $app;
