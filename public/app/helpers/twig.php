<?php

/**
 * [twig_hash_href description]
 * @param  [type] $key    [description]
 * @param  [type] $locale [description]
 * @param  [type] $page   [description]
 * @return [type]       [description]
 */
function twig_hash_href($key, $locale, $page = 'home')
{
    global $app;

    return $app['url_generator']->generate($page . '_' . $locale) . '#' . twig_hash_name($key, $locale);
}

/**
 * [twig_hash_name description]
 * @param  [type] $key    [description]
 * @param  [type] $locale [description]
 * @return [type]         [description]
 */
function twig_hash_name($key, $locale)
{
    global $app;

    return $app['translator']->trans($key . '_hash_' . $locale);
}