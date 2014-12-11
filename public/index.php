<?php

define('CONFIG_PATH', __DIR__ . '/../app/config/');

try {

    /**
     * Read the configuration
     */
    $config = new \Phalcon\Config\Adapter\Ini(CONFIG_PATH . 'config.ini');

    /**
     * Read auto-loader
     */
    include CONFIG_PATH . 'loader.php';

    /**
     * Read services
     */
    include CONFIG_PATH . 'services.php';

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
    echo $e->getMessage();
}
