<?php

use \Silex\Application;

/**
 * [extend description]
 *
 * @param  Application $app         [description]
 * @param  [type]      $service_key [description]
 * @param  [type]      $func        [description]
 * @return [type]                   [description]
 */
function extend(Application $app, $service_key, $func)
{
    $app[$service_key] = $app->share($app->extend($service_key, $func));
}