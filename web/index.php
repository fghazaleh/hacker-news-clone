<?php

ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

//config files
require __DIR__.'/../config/prod.php';
require __DIR__.'/../config/hacker-news.php';

//services files
//require __DIR__ . '/../bootstrap/services.php';

//routes files
require __DIR__ . '/../routes/web.php';
require __DIR__ . '/../routes/error.php';

//run the Silex application
$app->run();
