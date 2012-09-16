<?php

use Symfony\Component\HttpFoundation\Request;

$requireLogin    = function (Request $req) use ($app) {
    if (!$app['session']->has('admin')) {
        if ($req->isXmlHttpRequest())
            $app->json(array('error' => 'Unauthorized Access.'), 403);
        else
            return $app->redirect('/login');
    }
};

$mustBeAnonymous = function (Request $req) use ($app) {
    if ($app['session']->has('admin')) {
        if ($req->isXmlHttpRequest())
            $app->json(array('error' => 'Anonymous Acess Only.'), 400);
        else
            return $app->redirect('/logout');
    }
};

$requireAjax     = function(Request $req) use ($app) {
    if (!$req->isXmlHttpRequest())
        $app->abort(400, "Ajax Only.");
};

$requireRef      = function(Request $req) use ($app) {
    if (!$req->server->has('HTTP_REFERER') || parse_url($req->server->get('HTTP_REFERER'), PHP_URL_HOST) !== $req->server->get('HTTP_HOST'))
        $app->abort(400, "Bad Request.");
};