<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/', function (Request $req) use ($app) {
    $app['locale'] = 'nl';

    return $app['twig']->render('index.twig', array());

})->bind('home_nl');

$app->get('/en/', function (Request $req) use ($app) {
    $app['locale'] = 'en';

    return $app['twig']->render('index.twig', array());
})->bind('home_en');


