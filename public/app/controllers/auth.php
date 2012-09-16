<?php

use Symfony\Component\HttpFoundation\Request;

$auth = $app['controllers_factory'];

$auth->get('/login', function (Request $req) use ($app) {
    // TODO!
})->before($mustBeAnonymous)->bind('login');

$auth->get('/logout', function (Request $req) use ($app) {
    // TODO!
})->before($requireLogin)->bind('logout');

return $auth;