<?php

use Symfony\Component\HttpFoundation\Request;

$edit = $app['controllers_factory'];

$edit->post('/{lang}/{id}', function (Request $req, $lang, $id) use ($app) {
    // TODO!
})->before($requireAjax)->before($requireLogin)->bind('edit');

return $edit;