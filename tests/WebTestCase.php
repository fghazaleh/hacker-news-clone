<?php namespace Tests;

use Silex\WebTestCase as SilexWebTestCase;

abstract class WebTestCase extends SilexWebTestCase
{

    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        require __DIR__.'/../config/dev.php';
        require __DIR__.'/../config/hacker-news.php';
        require __DIR__ . '/../routes/web.php';
        require __DIR__ . '/../routes/error.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }
}