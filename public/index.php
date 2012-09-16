<?php

$appdir     = __DIR__ . '/app';
$autoloader = require $appdir . '/vendor/autoload.php';
$app        = new Silex\Application();

// register services
require $appdir . '/register.php';

// make before() callbacks for controller definitions available
require $appdir . '/helpers/before.php';

// include controllers
$app->mount('/auth',  require $appdir . '/controllers/auth.php');
$app->mount('/email', require $appdir . '/controllers/email.php');
$app->mount('/edit',  require $appdir . '/controllers/edit.php');

// include controllers for index/home/site :)
require $appdir . '/controllers/index.php';

$app->run();