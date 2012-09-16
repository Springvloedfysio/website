<?php

use Symfony\Component\Translation\Loader\YamlFileLoader;

require __DIR__ . '/helpers/register.php';

// config readers
$cvars = array(
    'data_path' => __DIR__ . '/../content'
);

$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__ . '/config/defaults.json', $cvars));
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__ . '/config/config.json', $cvars));

// url-generation
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// mailing
try {
    $mailer_opts = array('swiftmailer.options' => $app['smtp_settings']);
} catch (InvalidArgumentException $e) {
    $mailer_opts = array();
}

$app->register(new Silex\Provider\SwiftmailerServiceProvider(), $mailer_opts);
if (!count($mailer_opts))
    $app['swiftmailer.transport'] = \Swift_MailTransport::newInstance();


/*
// crufty swiftmail logger for dev purposes
$app['mailer.logger'] = new \Swift_Plugins_Loggers_EchoLogger();
$app['mailer']->registerPlugin(new \Swift_Plugins_LoggerPlugin($app['mailer.logger']));
// */

// templating
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path'         => __DIR__ . '/templates',
));

extend($app, 'twig', function($twig, $app) {
    require __DIR__ . '/helpers/twig.php';

    $twig->addGlobal('assets_min', $app['debug_assets'] ? '' : '.min');
    // $twig->addFilter('levenshtein', new \Twig_Filter_Function('levenshtein'));

    $twig->addFunction('hash_href', new \Twig_Function_Function('twig_hash_href'));
    $twig->addFunction('hash_name', new \Twig_Function_Function('twig_hash_name'));

    return $twig;
});

// translation
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallback'   => 'nl',
));

extend($app, 'translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());

    $translator->addResource('yaml', __DIR__ . '/locales/en.yml', 'en');
    $translator->addResource('yaml', __DIR__ . '/locales/nl.yml', 'nl');

    return $translator;
});



