<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->librariesDir,
        $config->application->librariesDir.'alipay',
        $config->application->librariesDir.'upmp'
    )
);

$loader->registerNamespaces(
    array(
       "Phalcon"    => $config->application->vendorDir."phalcon/incubator/Library/Phalcon/",
    )
);

$loader->register();

require $config->application->librariesDir.'helpers.php';
