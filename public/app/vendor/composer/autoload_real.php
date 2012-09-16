<?php

// autoload_real.php generated by Composer

require __DIR__ . '/ClassLoader.php';

class ComposerAutoloaderInit
{
    public static function getLoader()
    {
        $loader = new \Composer\Autoload\ClassLoader();
        $vendorDir = dirname(__DIR__);
        $baseDir = dirname(dirname(dirname($vendorDir)));

        $map = require __DIR__ . '/autoload_namespaces.php';
        foreach ($map as $namespace => $path) {
            $loader->add($namespace, $path);
        }

        $classMap = require __DIR__ . '/autoload_classmap.php';
        if ($classMap) {
            $loader->addClassMap($classMap);
        }

        $loader->register();

        require $vendorDir . '/swiftmailer/swiftmailer/lib/swift_required.php';

        return $loader;
    }
}